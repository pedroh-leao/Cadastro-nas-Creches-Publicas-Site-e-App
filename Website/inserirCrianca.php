<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroSalvo(){
            swal('Sucesso' ,'Registro salvo com sucesso!', 'success').then((value) => {
                window.location = "selecionarCrianca.php";
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível concluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }
          
    </script>
</head>
<body>
    
<?php
    //menu de navegação
    include_once("telaDeFundoResponsavel.php");
?> 

<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //receber os dados que veio do form via POST
    $nome = $_POST["nomeCompleto"];
    $data_de_nascimento = $_POST["dataNascimento"];
    $Cpfresponsavel = $_SESSION['cpf'];
    

    //criar o comando sql do insert
    $sql = "INSERT INTO tb_crianca (data_de_nascimento, nome, tb_responsavel_Cpf)
    VALUES ('$data_de_nascimento', '$nome', '$Cpfresponsavel')";

    //echo $sql;

    //executar o comando sql
    if ($conn->query($sql) === TRUE) {
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

?>
</body>
</html>