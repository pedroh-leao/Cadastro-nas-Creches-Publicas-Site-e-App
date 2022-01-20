<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Funcionário</title>

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
            //menu de navegação
            include_once("navegacaoInicial.html");
        ?>
        <div class="divCadastro">   
            <br>
            <div>
                <h1 id="titulo">Login</h1>
                <p id="subtitulo"> Complete com seus dados:</p><br>
            </div>
            <br>
         
            <form name="formLogin" action="checkLoginFuncionario.php" method="POST">

                <fieldset>
                    <div>
                        <label for="cpfLogin"><strong>Cpf:</strong></label><br>
                        <input type="text" name="cpfLogin" id="cpfLogin" class="inputLogin" maxlength="14" OnKeyPress="formatar('###.###.###-##', this)" ><br><br>
                    </div>
                    <div>
                        <label for="senhaLogin"><strong>Senha:</strong></label><br>
                        <input type="password" name="senhaLogin" id="senhaLogin" class="inputLogin"><br><br>
                    </div>
                </fieldset>

                <br>

                <div>
                    <button id="botaoLogin" type="submit" class="botaoLogin">Entrar</button>
                </div><br>

            </form>
        </div>
        <br><br><br><br><br>
    </body>
</html>