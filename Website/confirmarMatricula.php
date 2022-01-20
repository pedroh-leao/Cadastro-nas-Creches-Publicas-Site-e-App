<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");

    include_once("conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroSalvo(){
            swal('Sucesso' ,'A matrícula foi confirmada!', 'success').then((value) => {
                window.location = "solicitacoesParaConfirmar.php";
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível confirmar a matrícula!', 'error').then((value) => {
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

    //receber os dados que vieram do form via POST
    $idCrianca = $_GET["idCrianca"];
    $idCreche = $_GET["idCreche"];
    $idPeriodo = $_GET["idPeriodo"];


    //criar o comando sql do insert
    $sql = "UPDATE tb_cadastro SET isReserva = 0 WHERE tb_crianca_id = $idCrianca AND tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo";

    //echo $sql;

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
?>

</body>
</html>