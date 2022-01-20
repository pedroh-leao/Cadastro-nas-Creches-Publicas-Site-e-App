<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Funcionário</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarFuncionario.php";
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

    session_start();

    //isset verifica se foi setado algum valor para $_get[" cpf"]
    if(isset($_GET["CPF"])){

        $cpfFuncionario = $_GET["CPF"];

        $sql = "DELETE FROM tb_funcionario_secretaria WHERE CPF = '$cpfFuncionario' ";

        $sqlPeriodo = "SELECT * FROM tb_periodo_cadastro WHERE tb_funcionario_secretaria_CPF = '$cpfFuncionario' ";
        $consultaPeriodo = $conn->query($sqlPeriodo);
        $periodo = $consultaPeriodo->fetch_assoc(); 

        //if para verificar se existe periodo de matricula vinculado ao funcionário que está sendo excluído
        //caso exista, ele também será excluído, da tb_periodo_cadastro
        if(empty($periodo)){
                        
        }else{
            $sqlDelPeriodo = "DELETE FROM tb_periodo_cadastro WHERE tb_funcionario_secretaria_CPF = '$cpfFuncionario' ";
            $conn->query($sqlDelPeriodo);
        }

        if($conn->query($sql) === true ){
            if($_SESSION['cpf'] == $cpfFuncionario){
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