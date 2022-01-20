<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function senhaIncorreta(){
            swal('Senha incorreta!' ,'Tente novamente!', 'error').then((value) => {
                window.history.back();//simula o voltar do navegador
            });
        }
        function cpfInexistente(){
            swal('CPF não cadastrado!' ,'Tente acessar uma conta com CPF válido!', 'error').then((value) => {
                window.history.back();//simula o voltar do navegador
            });
        }
    </script>
</head>
<body>
    
<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    
    if($_SESSION["tipoUsuario"] == "responsavel"){
        //menu de navegação
        include_once("telaDeFundoResponsavel.php");
    }else{
        //menu de navegação
        include_once("telaDeFundoFuncionario.php");
    }
    

    //receber os dados que vieram do form via POST
    $cpf = $_POST["cpf"];
    $senha = $_POST["senhaAtual"];

    $sql = "SELECT * FROM tb_responsavel WHERE Cpf = '$cpf'";
    $consulta = $conn->query($sql);

    //verificando se o cpf existe
    if($consulta->num_rows == 0){
        ?>
        <script>
            cpfInexistente();
        </script>
        <?php
    }else{
        $usuario = $consulta->fetch_assoc();
    
        //verificando se a senha digitada($senha) é igual a senha criptografada no banco($usuario['senha'])
        if(password_verify($senha, $usuario['Senha'])){
    
                ?>
                <script>
                    window.location = "alterarSenhaResponsavel.php?cpf=<?php echo $cpf?>";
                </script>
    
                <?php
        }
        else{
            ?>
            <script>
                
                senhaIncorreta();
                
            </script>
    
            <?php
        }
    }

?>

</body>
</html>