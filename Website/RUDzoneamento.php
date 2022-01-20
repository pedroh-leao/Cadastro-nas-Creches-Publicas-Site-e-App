<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar dados</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function resgirtroAtualizado(){
            swal('Sucesso!' ,'Registro atualizado com sucesso!', 'success').then((value) => {
                //voltará para a tabela de creches na qual o funcionário tem acesso
                window.location = "selecionarZoneamento.php";
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


    if(isset($_POST["nomeFuncionario"])){
        $cpf = $_GET["CPF"];
        $nome = $_POST["nomeFuncionario"];
        

        //criar o comando sql do update
        $sqlUpdate = "UPDATE tb_zona
                    SET tb_bairro_id = '
                    WHERE CPF = '$cpf'";

        
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
        <h1 id="titulo">Dados do Zoneamento</h1><br>
    </div>
    <br>

    <?php
        if(isset($_GET["tb_creche_id"]) && isset($_GET["tb_bairro_id"])){
            $idCreche = $_GET["tb_creche_id"];
            $idBairro = $_GET["tb_bairro_id"];
            $sqlCreche = "SELECT * from tb_zona where tb_creche_id = $idCreche and tb_bairro_id = $idBairro ";
            $consulta = $conn->query($sqlCreche);
            $creche = $consulta->fetch_assoc(); 

            $sqlNomeCreche = "SELECT * from tb_creche where id = '$idCreche'";
            $consultaNomeCreche = $conn->query($sqlNomeCreche);
            $nomeCreche = $consultaNomeCreche->fetch_assoc(); 
        }
    ?>

    <form name="formZoneamento" action="" method="POST">

        <fieldset>
            <div>
                <label for="creche"><strong>Creche:</strong></label><br>
                <input type="text" name="creche" id="creche" value="<?php echo $nomeCreche["nome"] ?>"><br><br>
            </div>
            <div>
                <label for="bairros"><strong>Selecione os bairros permitidos:</strong></label><br>
                <label for="bairros" style="font-size: 14px"><strong>Segure "Ctrl" para selecionar mais de um</strong></label><br><br>
                <select name="bairro" id="bairro">
                    <?php
                        //incluir o bd
                        include_once('conexao.php');

                        //buscar dados do dropdown no BD(tb_bairro)
                        //criar o comando sql
                        $sqlBairro = "SELECT id, nome FROM tb_bairro ORDER BY nome";

                        //executar o comando sql
                        $bairro = $conn->query($sqlBairro);

                        while ($rowBairro = $bairro->fetch_assoc()) { 
                            ?>
                            <option value="<?php echo $rowBairro["id"]; ?>" <?php echo ($rowBairro["id"] == $creche["tb_bairro_id"]) ? "selected" : ""?> ><?php echo $rowBairro["nome"]; ?></option>
                            <?php
                        }
                        ?>
                </select><br><br>
            </div>
        </fieldset>

        <button id="botaoAlterarDados" type="submit" >Alterar dados</button>
        <button id="botaoExcluirDados" type="reset" onclick="confirmarExclusao(
                '<?php echo $exibir["tb_creche_id"] ?>',
                '<?php echo $exibir["tb_bairro_id"] ?>')">Excluir dados</button>

    </form>

    <br><br><br><br><br>
</body>

    <script>
        function confirmarExclusao(id, data_inicio, data_fim){
            swal({
                title: "Deseja realmente excluir o registro?",
                text: "Você irá deletar o zoneamento selecionado!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                if(willDelete){
                    window.location = "excluirZoneamento.php?idBairro=" + idBairro;
                };
            });
        }
    </script>
</html>