<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroAtualizado(){
            swal('Sucesso' ,'Registro atualizado com sucesso!', 'success').then((value) => {
                window.location = "menuSecretaria.php";
            });
        }

        function erroRegistroAtualizado(){
            swal('Erro' ,'Os campos de senha devem estar iguais!', 'error').then((value) => {
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
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");

    //receber os dados que vieram do form via POST
    $senha = $_POST["novaSenha"];
    $confirmarSenha = $_POST["confirmarNovaSenha"];
    $cpf = $_GET['cpf'];

    //verifica se os campos de senha estão iguais
    if($senha == $confirmarSenha){

        //criando uma variavel para armazenar a senha sem criptografia para mostrar para o usuário
        //$mostraSenha = $senha;

        //criptografando a senha
        $senha = password_hash($_POST["novaSenha"], PASSWORD_BCRYPT);

        //criar o comando sql do insert
        $sqlUpdate = "UPDATE tb_funcionario_secretaria SET senha = '$senha' WHERE CPF = '$cpf'";

        //echo $sql;

        $conn->query($sqlUpdate);

        ?>
        
        <script>
            registroAtualizado();
        </script>
        <?php
        
    }else{
        ?>
        <script>
            erroRegistroAtualizado();
        </script>
        <?php
    }


?>
</body>
</html>