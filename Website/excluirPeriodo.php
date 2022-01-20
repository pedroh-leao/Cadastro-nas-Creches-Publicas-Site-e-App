<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Período de matricula</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarPeriodo.php";
            });
        }

        function naoExclusao(){
            swal('Erro' ,'Não foi possível excluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function naoExclusaoPeriodo(){
            swal('Erro' ,'Não foi possível excluir o registro pois existem creches cadastradas nesse periodo!!', 'error').then((value) => {
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

    //isset verifica se foi setado algum valor para $_get["id"]
    if(isset($_GET["id"])){

        $idPeriodo = $_GET["id"];

        $sql = "DELETE FROM tb_periodo_cadastro WHERE id = $idPeriodo";

        if($conn->query($sql) === true ){
            ?>
            <script>
                exclusao();
            </script>

            <?php

        }
        else{

            //Verificando se tem alguma vaga cadastrada nesse período
            $sqlConsultVagas = "SELECT * FROM tb_periodo_cadastro_tb_creche WHERE tb_periodo_cadastro_id = $idPeriodo";

            $verificaVagas = $conn->query($sqlConsultVagas);

            //caso tenha alguma vaga cadastrada nesse período, essa vaga também será excluida 
            if($verificaVagas -> num_rows > 0){
                ?>
                <script>
                    naoExclusaoPeriodo();
                </script>
                <?php
            }

            ?>
            <script>
                naoExclusao();
            </script>

            <?php

        }

    }

?>
</body>
</html>
