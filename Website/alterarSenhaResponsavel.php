<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="estilo.css">
</head>



<body>
    <?php
        if($_SESSION["tipoUsuario"] == "responsavel"){
            //menu de navegação
            include_once("navegacaoResponsavel.html");
        }else{
            //menu de navegação
            include_once("navegacaoFuncionario.html");
        }

        $cpf = $_GET["cpf"];
    ?>
    <div class="divRUD">   
        <br>
        <form name="formAlterarSenha" action="updateSenhaResponsavel.php?cpf=<?php echo $cpf ?>" method="POST">

            <fieldset>                
                <div>
                    <label for="novaSenha"><strong>Digite a nova senha:</strong></label><br>
                    <input type="password" class="confirmarSenha" name="novaSenha" id="novaSenha">
                </div><br><br>
                <div>
                    <label for="confirmarNovaSenha"><strong>Confirme a nova senha:</strong></label><br>
                    <input type="password" class="confirmarSenha" name="confirmarNovaSenha" id="confirmarNovaSenha">
                </div><br><br>
            </fieldset>

            <button id="botaoAlterarSenha">Confirmar</button><br><br>

        </form>

    </div>
</body>

</html>