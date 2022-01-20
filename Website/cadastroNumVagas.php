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
        <title>Cadastro de Vagas</title>

        <link rel="stylesheet" href="estilo.css">
        
    </head>
    <body>

        <?php
            //menu de navegação
            include_once("navegacaoFuncionario.html");
        ?>

        <div> 
            <h1 id="titulo">Número de Vagas na Creche para um Período</h1>
            <p id="subtitulo"> Complete com as informações:</p><br>
        </div>
        
        <div class="divCadastro">   
        <br>
            <form name="formVagas" action="inserirNumVagas.php" method="post">

                <fieldset>    
                    <div>
                        <label for="periodoMatricula"><strong>Período de Matrícula Referente:</strong></label><br>
                        <select style="font-size:  18px;" name="periodoMatricula" id="periodoMatricula" class="selectCadastro" >
                            <?php
                                //incluir o bd
                                include_once('conexao.php');

                                //buscar dados do dropdown no BD(tb_periodo_cadastro)
                                //criar o comando sql
                                $sqlPeriodo = "SELECT id, data_inicio, data_fim FROM tb_periodo_cadastro ORDER BY data_inicio";

                                //executar o comando sql
                                $periodos = $conn->query($sqlPeriodo);

                                while ($rowPeriodos = $periodos->fetch_assoc()) { 
                                    ?>
                                    <option value="<?php echo $rowPeriodos["id"]; ?>">
                                        <?php echo date('d/m/Y',strtotime($rowPeriodos["data_inicio"])) . " - " . date('d/m/Y',strtotime($rowPeriodos["data_fim"])); ?>
                                    </option>
                                    <?php
                                }
                                ?>
                        </select><br><br>
                    </div>            
                    <div>
                        <label for="creche"><strong>Creche:</strong></label><br>
                        <select name="creche" id="creche" class="selectCadastro" >
                            <?php
                                //incluir o bd
                                include_once('conexao.php');

                                //buscar dados do dropdown no BD(tb_creche)
                                //criar o comando sql
                                $sqlCreche = "SELECT id, nome FROM tb_creche ORDER BY nome";

                                //executar o comando sql
                                $creches = $conn->query($sqlCreche);

                                while ($rowCreche = $creches->fetch_assoc()) { 
                                    ?>
                                    <option value="<?php echo $rowCreche["id"]; ?>"><?php echo $rowCreche["nome"]; ?></option>
                                    <?php
                                }
                            ?>
                        </select><br><br>
                    </div>                 
                    <div>
                        <label for="numVagas"><strong>Número de Vagas:</strong></label><br>
                        <input type="number" name="numVagas" id="numVagas" class="inputCadastro"><br><br>
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