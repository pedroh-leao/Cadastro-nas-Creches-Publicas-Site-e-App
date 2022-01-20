<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar senha</title>
    <link rel="stylesheet" href="estilo.css"> 

    <script>
        function formatar(mascara, documento){
            var i = documento.value.length;
            var saida = mascara.substring(0,1);
            var texto = mascara.substring(i)
            
            if (texto.substring(0,1) != saida){
                documento.value += texto.substring(0,1);
            }
        
        }
    </script>

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
        ?>
    <div class="divRUD">   
        <br>
        <form name="formConfirmarSenha" action="checkSenhaResponsavel.php" method="POST">

            <fieldset>
                <div>
                    <label for="cpf"><strong>Digite o CPF da conta</strong></label><br><br>
                    <input type="text" name="cpf" id="cpf" class="confirmarSenha" maxlength="14" OnKeyPress="formatar('###.###.###-##', this)">
                </div><br><br>
                <div>
                    <label for="senhaAtual"><strong>Digite a senha atual</strong></label><br><br>
                    <input type="password" name="senhaAtual" class="confirmarSenha" id="senhaAtual">
                </div><br><br>
            </fieldset>

            <button id="botaoSenhaAtual">Confirmar</button><br><br>

        </form>
    </div>
    <br><br><br><br><br>

</body>

</html>