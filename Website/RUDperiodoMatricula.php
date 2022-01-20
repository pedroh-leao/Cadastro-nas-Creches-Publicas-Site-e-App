<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="estilo.css">
        <title>Cadastro do periodo de matricula</title>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
        function resgirtroAtualizado(){
            swal('Sucesso!' ,'Registro atualizado com sucesso!', 'success').then((value) => {
                //voltará para a tabela de creches na qual o funcionário tem acesso
                window.location = "selecionarPeriodo.php";
            });
        }

        function erroResgirtroAtualizado(){
            swal('Erro!' ,'Erro ao atualizar o registro!', 'error').then((value) => {
                window.history.back();//simula o voltar do navegador
            });
        }
        </script>
    </head>

    <body>

<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");
    
    include_once("conexao.php");

    if(isset($_POST["dataInicio"])){
        $id = $_GET["id"];
        $data_inicio = $_POST["dataInicio"];
        $data_fim = $_POST["dataFim"];
        $hora_inicio = $_POST["horaInicio"];
        $hora_fim = $_POST["horaFim"];
        $cpf_funcionario = $_SESSION['cpf'];

        $sqlUpdate = "UPDATE tb_periodo_cadastro
                    SET data_inicio = '$data_inicio', 
                    data_fim = '$data_fim',
                    hora_inicio = '$hora_inicio',
                    hora_fim = '$hora_fim',
                    tb_funcionario_secretaria_CPF = '$cpf_funcionario'
                    WHERE id = $id";

        
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
            <h1 id="titulo">Dados do periodo de matricula</h1>            
        </div>
        <br>

        <?php
        if(isset($_GET["id"])){
            $idPeriodo = $_GET["id"];
            $sqlperiodo = "SELECT * from tb_periodo_cadastro where id = $idPeriodo";
            $consulta = $conn->query($sqlperiodo);
            $periodo = $consulta->fetch_assoc(); 
        }
        ?>
        <div class="divRUD">   
            <br>
        
            <form name="formPeriodoCadastro" action="RUDperiodoMatricula.php?id=<?php echo $_GET['id']?>" method="POST">
        
                <fieldset>

                
                
                    <div>
                        <label for="dataInicio"><strong>Data de início</strong></label><br>
                        <input type="date" name="dataInicio" id="dataInicio" class="inputEditar" value="<?php echo $periodo["data_inicio"] ?>"><br><br>
                    </div>
                    <div>
                        <label for="dataFim"><strong>Data final de cadastro:</strong></label><br>
                        <input type="date" name="dataFim" id="dataFim" class="inputEditar" value="<?php echo $periodo["data_fim"] ?>"><br><br>
                    </div>
                    <div>
                        <label for="horaInicio"><strong>Horário de início:</strong></label><br>
                        <input type="time" name="horaInicio" id="horaInicio" class="inputEditar" value="<?php echo $periodo["hora_inicio"] ?>"><br><br>
                    </div>
                    <div>
                        <label for="horaFim"><strong>Horário final:</strong></label><br>
                        <input type="time" name="horaFim" id="horaFim" class="inputEditar" value="<?php echo $periodo["hora_fim"] ?>"><br><br>
                    </div>
                    
                    
                </fieldset>
        
                <br>
        
                <div>
                    <button id="botaoAlterarDados" type="submit" >Alterar dados</button>
                    <button id="botaoExcluirDados" type="reset" onclick ="confirmarExclusao(
                        '<?php echo $periodo["id"] ?>',
                        '<?php echo $periodo["data_inicio"] ?>',
                        '<?php echo $periodo["data_fim"] ?>')" >Excluir dados</button><br><br>
                </div>
        
            </form>
        </div>
        <br><br><br><br><br>
    
    </body>

    <script>
    function confirmarExclusao(id, data_inicio, data_fim){
        swal({
            title: "Deseja realmente excluir o registro?",
            text: "Você irá deletar o período selecionado!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
            if(willDelete){
                window.location = "excluirPeriodo.php?id=" + id;
            };
        });
    }
    </script>

</html>