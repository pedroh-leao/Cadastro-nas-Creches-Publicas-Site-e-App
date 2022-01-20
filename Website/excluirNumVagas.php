<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir número de vagas</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarNumVagas.php";
            });
        }

        function naoExclusao(){
            swal('Erro' ,'Não foi possível excluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }
          
        function criancaCadastrada(){
            swal('Erro' ,'Existe uma ou mais crianças cadastradas nessa creche nesse período de matrícula!', 'error').then((value) => {
                window.history.back();
            });
        }
    </script>
</head>
<body>
    <?php
        //menu de navegação
        include_once("telaDeFundoFuncionario.php");
    ?> 
    

<?php 

    include_once("conexao.php");

    //isset verifica se foi setado algum valor para as variaveis recebidas via GET
    if(isset($_GET["tb_periodo_cadastro_id"]) && isset($_GET["tb_creche_id"])){

        $idPeriodo = $_GET["tb_periodo_cadastro_id"];
        $idCreche = $_GET["tb_creche_id"];

        $sql = "DELETE FROM tb_periodo_cadastro_tb_creche WHERE tb_periodo_cadastro_id = $idPeriodo AND tb_creche_id = $idCreche";

        //sql para verificar se já existem crianças cadastradas nessa creche nesse período
        $sqlVerifica = "SELECT * FROM tb_cadastro WHERE tb_periodo_cadastro_id = $idPeriodo AND tb_creche_id = $idCreche";
        $retornoVerificacao = $conn->query($sqlVerifica);
        
        if($retornoVerificacao->num_rows > 0){
            ?>
            <script>
                criancaCadastrada();
            </script>

            <?php
        }else{
            if($conn->query($sql) === TRUE ){
                ?>
                <script>
                    exclusao();
                </script>
    
                <?php
            }
            else{
                ?>
                <script>
                    naoExclusao();
                    
                </script>
    
                <?php
            }
        }
    }

?>
</body>
</html>