<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilo.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroSalvo(){
            swal('Sucesso' ,'Registro salvo com sucesso!', 'success').then((value) => {
                window.location = "index.php";
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível concluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function camposDiferentes(){
            swal('Erro' ,'Os campos de senha devem estar iguais!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function cpfCadastrado(){
            swal('Erro' ,'Esse CPF já está cadastrado!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function emailCadastrado(){
            swal('Erro' ,'Esse email já está cadastrado em outra conta!!', 'error').then((value) => {
                window.history.back();
            });
        } 
    </script>
</head>
<body>

<?php
    //menu de navegação
    include_once("navegacaoInicial.html");
?> 
    

<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //receber os dados que vieram do form via POST
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $nome = $_POST["nomeFuncionario"];
    $senha = $_POST["senha"];
    $confirmarSenha = $_POST["confirmarSenha"];
    $cargo = $_POST["cargo"];
    $autorizado = 0; //0 == false

    //verifica se os campos de senha estão iguais
    if($senha == $confirmarSenha){

        //criptografando a senha
        $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT);

        //criar o comando sql do insert
        $sql = "INSERT INTO tb_funcionario_secretaria (CPF, e_mail, nome, senha, cargo_funcionario, autorizado)
        VALUES ('$cpf', '$email', '$nome', '$senha', '$cargo', $autorizado)";

        //echo $sql;

        //verifica se já existe conta cadastrada com o cpf fornecido
        $sqlVerifica = "SELECT * FROM tb_funcionario_secretaria WHERE CPF = '$cpf'";
        $consultaVerificacao = $conn->query($sqlVerifica);

        //verifica se já existe conta cadastrada com o EMAIL fornecido
        $sqlVerifEmail = "SELECT * FROM tb_funcionario_secretaria WHERE e_mail = '$email'";
        $consultaEmail = $conn->query($sqlVerifEmail);

        if($consultaVerificacao->num_rows > 0){
            ?>
            <script>
                cpfCadastrado();
            </script>
            <?php
        }elseif($consultaEmail->num_rows > 0){
            ?>
            <script>
                emailCadastrado();
            </script>
            <?php
        }else{

            //executar o comando sql
            if($conn->query($sql) === TRUE) {
                ?>
                <script>
                    registroSalvo();
                </script>

                <?php
            }
            else{
                ?>
                <script>
                    erroRegistroSalvo();
                </script>

                <?php
            }
        }
    }else{
        ?>
        <script>
            camposDiferentes();
        </script>
        <?php
    }
?>
</body>
</html>