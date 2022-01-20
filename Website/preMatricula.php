<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isResponsavel.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matricular</title>

    <link rel="stylesheet" href="estilo.css">
</head>
    <body>
        <?php
            //menu de navegação
            include_once("navegacaoResponsavel.html");
        ?>
        <br><br>

        <h2><strong>Se os dados da criança já estão cadastrados no site:</strong></h2>

        <br><br>

        <form action="solicitarMatricula.php">
            <div>
                <button id="botaoCadastro" type="submit" >Matricular criança</button>
            </div>
        </form>

        <br><br><br><br><br>

        <h2><strong>Caso os dados da criança ainda não estejam cadastrados:</strong></h2>

        <br><br>

        <form action="cadastroCrianca.php">
            <div>
                <button id="botaoCadastro" type="submit" >Cadastrar dados da criança</button>
            </div>
        </form>

        

        <br><br><br><br><br>
    </body>
</html>