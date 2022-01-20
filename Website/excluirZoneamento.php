<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Zoneamento</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarZoneamento.php";
            });
        }

        function naoExclusao(){
            swal('Erro' ,'Não foi possível excluir o registro!!', 'error').then((value) => {
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
    if(isset($_GET["idBairro"])){

        $idBairro = $_GET["idBairro"];

        $sql = "DELETE FROM tb_zona WHERE tb_bairro_id = $idBairro";

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

?>
</body>
</html>