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
                window.location = "selecionarPeriodo.php";
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
    include_once("telaDeFundoFuncionario.php");
?> 
    

<?php
//incluir o aquivo de conexão com o BD
include_once("conexao.php");

//receber os dados que veio do form via POST
$data_inicio = $_POST["dataInicio"];
$data_fim = $_POST["dataFim"];
$hora_inicio = $_POST["horaInicio"];
$hora_fim = $_POST["horafim"];
$CpfFuncionario = $_SESSION['cpf'];

//criar o comando sql do insert
$sql = "INSERT INTO tb_periodo_cadastro (data_inicio, data_fim, hora_inicio, hora_fim, tb_funcionario_secretaria_CPF)
VALUES ('$data_inicio', '$data_fim', '$hora_inicio', '$hora_fim', '$CpfFuncionario')";

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