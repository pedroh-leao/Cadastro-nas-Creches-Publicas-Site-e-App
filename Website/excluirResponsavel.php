<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Perfil</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarResponsavel.php";
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


    //isset verifica se foi setado algum valor para $_get["Cpf"]
    if(isset($_GET["Cpf"])){

        $cpfResponsavel = $_GET["Cpf"];

        

        $sql = "DELETE FROM tb_responsavel WHERE Cpf = '$cpfResponsavel' ";

        $sqlCrianca = "SELECT * FROM tb_crianca WHERE tb_responsavel_Cpf = '$cpfResponsavel' ";
        $consulta = $conn->query($sqlCrianca);
        $crianca = $consulta->fetch_assoc(); 

        //if para verificar se existe crianca vinculada ao responsável que está sendo excluído
        //caso exista, ela também será excluída, da tb_crianca
        if(empty($crianca)){            
            
        }else{
            $sqlDelCrianca = "DELETE FROM tb_crianca WHERE tb_responsavel_Cpf = '$cpfResponsavel' ";
            $conn->query($sqlDelCrianca);
        }

        if($conn->query($sql) === true ){
            if($_SESSION['tipoUsuario'] == "responsavel"){
                //destrói a session
                session_destroy();
                ?>
                <script>
                    exclusao();
                </script>
                <?php
            }
            else{
            ?>
                <script>
                    exclusao();
                </script>

            <?php
            }
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