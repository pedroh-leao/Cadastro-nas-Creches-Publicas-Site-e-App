<!DOCTYPE html>
<html lang="pt-br">
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
    $nome = $_POST["nomeCompleto"];
    $data_de_nascimento = $_POST["dataNascimento"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $senha = $_POST["senha"];
    $confirmarSenha = $_POST["confirmarSenha"];
    $idBairro = $_POST["bairro"];
    $endereco = $_POST["endereco"];

    //verifica se os campos de senha estão iguais
    if($senha == $confirmarSenha){

        //criando uma variavel para armazenar a senha sem criptografia para mostrar para o usuário
        //$mostraSenha = $senha;

        //criptografando a senha
        $senha = password_hash($_POST["senha"], PASSWORD_BCRYPT);

        //criar o comando sql do insert
        $sql = "INSERT INTO tb_responsavel (Cpf, Nome, data_de_nascimento, e_mail, telefone, Senha, tb_bairro_id, endereco)
        VALUES ('$cpf', '$nome', '$data_de_nascimento', '$email', '$telefone', '$senha', '$idBairro', '$endereco')";

        //echo $sql;

        //verifica se já existe conta cadastrada com o CPF fornecido
        $sqlVerifica = "SELECT * FROM tb_responsavel WHERE Cpf = '$cpf'";
        $consultaVerificacao = $conn->query($sqlVerifica);

        //verifica se já existe conta cadastrada com o EMAIL fornecido
        $sqlVerifEmail = "SELECT * FROM tb_responsavel WHERE e_mail = '$email'";
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