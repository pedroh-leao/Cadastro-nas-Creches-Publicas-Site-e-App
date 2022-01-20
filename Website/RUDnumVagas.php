<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visualizar número de vagas</title>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
        function resgirtroAtualizado(){
            swal('Sucesso!' ,'Registro atualizado com sucesso!', 'success').then((value) => {
                //voltará para a tabela de creches na qual o funcionário tem acesso
                window.location = "selecionarNumvagas.php";
            });
        }

        function erroResgirtroAtualizado(){
            swal('Erro!' ,'Erro ao atualizar o registro!', 'error').then((value) => {
                window.history.back();//simula o voltar do navegador
            });
        }
        </script>

        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>

    <?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");

    include_once("conexao.php");

    if(isset($_POST["numVagas"])){
        $idPeriodoMatricula = $_GET["tb_periodo_cadastro_id"];
        $CrecheID = $_GET["tb_creche_id"];
        $numVagas = $_POST["numVagas"];

        $sqlUpdate = "UPDATE tb_periodo_cadastro_tb_creche
                    SET numVagas = $numVagas 
                    WHERE tb_periodo_cadastro_id = $idPeriodoMatricula AND tb_creche_id = $CrecheID";

        
        if($conn->query($sqlUpdate) === TRUE){
            ?>
            <script>
                resgirtroAtualizado();
            </script>
            <?php
        }
        else{
            ?>
            <script>
                erroResgirtroAtualizado();
            </script>
            <?php
        }
    }
?>

        <?php
            //menu de navegação
            include_once("navegacaoFuncionario.html");
        ?>   

        <div> 
            <h1 id="titulo">Dados das creches</h1>
            <p id="subtitulo"> Veja as informações:</p><br>
        </div>
        <br>

        <?php
        if(isset($_GET["tb_periodo_cadastro_id"]) && isset($_GET["tb_creche_id"])){
            $idPeriodo = $_GET["tb_periodo_cadastro_id"];
            $idCreche = $_GET["tb_creche_id"];

            //consultando período de matrícula de acordo com o id
            $sqlperiodo = "SELECT data_inicio, data_fim from tb_periodo_cadastro where id = $idPeriodo";
            $consultaPeriodo = $conn->query($sqlperiodo);
            $periodo = $consultaPeriodo->fetch_assoc(); 

            //consultando nome da creche de acordo com o id
            $sqlcreche = "SELECT nome from tb_creche where id = $idCreche";
            $consulta = $conn->query($sqlcreche);
            $creche = $consulta->fetch_assoc(); 

            //consultando numero de vagas
            $sqlvagas = "SELECT numVagas FROM tb_periodo_cadastro_tb_creche WHERE tb_periodo_cadastro_id = $idPeriodo AND tb_creche_id = $idCreche";
            $consultaVagas = $conn->query($sqlvagas);
            $vagas = $consultaVagas->fetch_assoc();
        }
        ?>
        <div class="divRUD">   
            <br>

            <form name="formVagas" action="RUDnumVagas.php?tb_periodo_cadastro_id=<?php echo $idPeriodo ?>&tb_creche_id=<?php echo $idCreche ?>" method="post">
                <fieldset>    
                    <div>
                        <label for="periodoMatricula"><strong>Período de Matrícula Referente:</strong></label><br>
                        <input type="text" name="periodoMatricula" id="periodoMatricula" class="inputEditar" style="font-size:  18px;" 
                        value="<?php echo date('d/m/Y',strtotime($periodo["data_inicio"])) . " - " . date('d/m/Y',strtotime($periodo["data_fim"])) ?>" disabled><br><br>
                    </div>            
                    <div>
                        <label for="creche"><strong>Creche:</strong></label><br>
                        <input type="text" name="creche" id="creche" class="inputEditar" value="<?php echo $creche["nome"] ?>" disabled><br><br>
                    </div>                
                    <div>
                        <label for="numVagas"><strong>Número de Vagas:</strong></label><br>
                        <input type="number" name="numVagas" id="numVagas" class="inputEditar" value="<?php echo $vagas["numVagas"] ?>"><br><br>
                        <label for=""><b>Observação:</b> é possível alterar <b style="color: red;">apenas</b> o número de vagas.</label>
                    </div>
                </fieldset>

                <br>

                <div>
                    <button id="botaoAlterarDados" type="submit" >Alterar dados</button>
                    <button id="botaoExcluirDados" type="reset" onclick= "confirmarExclusao(
                        '<?php echo date('d/m/Y',strtotime($periodo["data_inicio"])) . " - " . date('d/m/Y',strtotime($periodo["data_fim"])) ?>',
                        '<?php echo $creche["nome"] ?>',
                        '<?php echo $idPeriodo ?>',
                        '<?php echo $idCreche ?>')">Excluir dados</button><br><br>

                </div>

            </form>  
        </div>
        
        <br><br><br><br><br>

    </body>

    <script>
        function confirmarExclusao(periodo, creche, idPeriodo, idCreche){
            swal({
                title: "Deseja realmente excluir o registro?",
                text: "Você irá deletar o numero de vagas selecionado!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                if(willDelete){
                    window.location = "excluirNumVagas.php?tb_periodo_cadastro_id=" + idPeriodo + "&tb_creche_id=" + idCreche;
                };
            });
        }
    </script>
</html>