<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecione o cadastro</title>

    <link rel="stylesheet" href="estilo.css">
    <style>
        #menuInicial ul li:last-child a {
        background-color: rgb(24, 139, 233);
        border-radius: 2px;
        }

        #botaoCadastrarMenuInicial{
            float: right;
            background-color: 
            rgb(24, 139, 233); 
            margin-left: 4px;
        }
    </style>
</head>
<body>
    <?php
        //menu de navegação
        include_once("navegacaoInicial.html");
    ?>

    <h1><strong>Você é?</strong></h1>

    <br><br><br>

    <form action="cadastroResponsavel.php">
        <div>
            <button id="botaoCadastro" type="submit" >Responsável</button>
        </div>
    </form>

    <br><br>

    <form action="cadastroFuncionario.php">
        <div>
            <button id="botaoCadastro" type="submit" >Funcionário</button>
        </div>
    </form>

    

    <br><br><br><br><br>
</body>
</html>