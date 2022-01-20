<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="estilo.css">

        <title>Cadastro do periodo de matricula</title>
    </head>

    <body>

        <?php
            //menu de navegação
            include_once("navegacaoFuncionario.html");
        ?>
        
        <div> 
            <h1 id="titulo">Cadastro do periodo de matricula</h1>
            <p id="subtitulo"> Complete as informações:</p><br>
        </div>
       
        <div class="divCadastro">   
        <br> 
            <form name="formPeriodoCadastro" action="inserirPeriodo.php" method="POST">
        
                <fieldset>
                    
                    <div>
                        <label for="dataInicio"><strong>Data de início</strong></label><br>
                        <input type="date" name="dataInicio" id="dataInicio" class="inputCadastro"><br><br>
                    </div>
                    <div>
                        <label for="dataFim"><strong>Data final de cadastro:</strong></label><br>
                        <input type="date" name="dataFim" id="dataFim" class="inputCadastro"><br><br>
                    </div>
                    <div>
                        <label for="horaInicio"><strong>Horário de início:</strong></label><br>
                        <input type="time" name="horaInicio" id="horaInicio" class="inputCadastro"><br><br>
                    </div>
                    <div>
                        <label for="horaFim"><strong>Horário final:</strong></label><br>
                        <input type="time" name="horafim" id="horafim" class="inputCadastro"><br><br>
                    </div>
                    
                </fieldset>
        
                <br>
        
                <div>
                    <button id="botaoCadastro" type="submit" >Concluído</button>
                </div><br>
            
            </form>
        </div>

        <br><br><br><br><br>
    </body>
</html>