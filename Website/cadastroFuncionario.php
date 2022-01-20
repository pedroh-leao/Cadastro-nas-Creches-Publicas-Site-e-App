<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro do Funcionário</title>

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

    <div> 
        <h1 id="titulo">Cadastro do Funcionário</h1>
        <p id="subtitulo"> Complete com as informações:</p><br>
    </div>
    
    <div class="divCadastro">   
    <br> 
        <form name="formFuncionario" action="inserirFuncionario.php" method="POST">

            <fieldset>
                <div>

                    <label for="nomeFuncionario"><strong>Nome:</strong></label><br>
                    <input type="text" name="nomeFuncionario" id="nomeFuncionario" class="inputCadastro"><br><br>

                </div>
                <div>
                    <label for="cpf"><strong>CPF:</strong></label><br>
                    <input type="text" name="cpf" id="cpf" maxlength="14" class="inputCadastro" OnKeyPress="formatar('###.###.###-##', this)"><br><br>
                </div>
                <div>                
                    <label for="cargo"><strong>Cargo:</strong></label><br>
                    <input type="text" name="cargo" id="cargo" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="email"><strong>E-mail:</strong></label><br>
                    <input type="email" name="email" id="email" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="senha"><strong>Senha:</strong></label><br>
                    <input type="password" name="senha" id="senha" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="confirmarSenha"><strong>Confirmar senha:</strong></label><br>
                    <input type="password" name="confirmarSenha" id="confirmarSenha" class="inputCadastro"><br><br>
                </div>  
            </fieldset>
        
            <div>
                <button id="botaoCadastro" type="submit" >Concluído</button>
            </div><br>
        </div> 
    </form>
    <br><br><br><br><br>
    
</body>
</html>