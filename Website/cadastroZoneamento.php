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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>

        <title>Cadastro do Zoneamento</title>
    </head>

    <body>
        <?php
            //menu de navegação
            include_once("navegacaoFuncionario.html");
        ?>
        
        <div> 
            <h1 id="titulo">Cadastro do Zoneamento</h1>
            <p id="subtitulo"> Complete as informações:</p><br>
        </div>

        <div class="divCadastro">   
        <br> 
        
            <form name="formZoneamento" action="inserirZoneamento.php" method="POST">
        
                <fieldset>
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
                        <label for="bairros"><strong>Selecione os bairros permitidos:</strong></label><br>
                        <label for="bairros" style="font-size: 14px"><strong>Segure "Ctrl" para selecionar mais de um</strong></label><br><br>
                        <select name="bairro[]" id="bairro" multiple class="selectCadastro" >
                        <?php
                            //incluir o bd
                            include_once('conexao.php');

                            //buscar dados do dropdown no BD(tb_bairro)
                            //criar o comando sql
                            $sqlBairro = "SELECT id, nome FROM tb_bairro ORDER BY nome";

                            //executar o comando sql
                            $bairro = $conn->query($sqlBairro);

                            while ($rowBairro = $bairro->fetch_assoc()) { 
                                ?>
                                <option value="<?php echo $rowBairro["id"]; ?>"><?php echo $rowBairro["nome"]; ?></option>
                                <?php
                            }
                            ?>
                        </select><br><br>

                        
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