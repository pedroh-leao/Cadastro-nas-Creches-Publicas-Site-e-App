<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Criança</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarCrianca.php";
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
    include_once("checkIsLogged.php");
    if($_SESSION['tipoUsuario'] == "funcionario"){
        //tela de fundo
        include_once("telaDeFundoFuncionario.php");
    }else{
        //tela de fundo
        include_once("telaDeFundoResponsavel.php");
    }
    
    
?> 
    
<?php 

    include_once("conexao.php");

    //isset verifica se foi setado algum valor para $_get["id"]
    if(isset($_GET["id"])){

        $idCrianca = $_GET["id"];

        $sql = "DELETE FROM tb_crianca WHERE id = $idCrianca";

        if($conn->query($sql) === true ){
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
</html><?php 