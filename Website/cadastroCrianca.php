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

    <link rel="stylesheet" href="estilo.css">

    <title>Cadastro da Criança</title>
</head>

<body>
    <?php
        //menu de navegação
        include_once("navegacaoResponsavel.html");
    ?>
    <div>
        <h1 id="titulo">Cadastro da Criança</h1>
        <p id="subtitulo"> Complete as informações:</p><br>
    </div>
    
    <div class="divCadastro">   
    <br>
        <form name="formCrianca" action="inserirCrianca.php" method="POST">

            <fieldset>
                <div>
                    <label for="nomeCompleto"><strong>Nome Completo:</strong></label><br>
                    <input type="text" name="nomeCompleto" id="nomeCompleto" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="dataNascimento"><strong>Data de nascimento:</strong></label><br>
                    <input type="date" name="dataNascimento" id="dataNascimento" class="inputCadastro"><br><br>
                    
                </div>

            </fieldset>

            <br>

            <div>
                <button id="botaoCadastro" type="submit">Concluído</button>
            </div><br>
    </div>
    </form>
    <br><br><br><br><br>

</body>

</html>