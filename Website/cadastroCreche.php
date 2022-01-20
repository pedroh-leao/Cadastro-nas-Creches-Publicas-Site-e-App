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
    <title>Cadastro da Creche</title>

    <link rel="stylesheet" href="estilo.css">
    
</head>
<body>

    <?php
        //menu de navegação
        include_once("navegacaoFuncionario.html");
    ?>

    <div> 
        <h1 id="titulo">Cadastro da creche</h1>
        <p id="subtitulo"> Complete com as informações:</p><br>
    </div>
    
    <div class="divCadastro">   
    <br>
        <form name="formCreche" action="inserirCreche.php" method="post">

            <fieldset>
                <div>
                    <label for="nomeCreche"><strong>Nome:</strong></label><br>
                    <input type="text" name="nomeCreche" id="nomeCreche" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="bairro"><strong>Bairro:</strong></label><br>
                    <select name="bairro" id="bairro" class="selectCadastro" >
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

                        <!--
                        <option value="1 de Maio">1° de Maio</option>
                        <option value="Alto Chalé">Alto Chalé</option>
                        <option value="Alto São Francisco">Alto São Francisco</option>
                        <option value="Amália Rodrigues">Amália Rodrigues</option>
                        <option value="Bandeirantes">Bandeirantes</option>
                        <option value="Bela Vista">Bela Vista</option>
                        <option value="Belvedere">Belvedere</option>
                        <option value="Campo Grande">Campo Grande</option>
                        <option value="Carreiras">Carreiras</option>
                        <option value="Castiliano">Castiliano</option>
                        <option value="Centro">Centro</option>
                        <option value="Dom Orionte">Dom Orionte</option>
                        <option value="Inconfidentes">Inconfidentes</option>
                        <option value="Itatiaia">Itatiaia</option>
                        <option value="Jardim Belo Horizonte">Jardim Belo Horizonte</option>
                        <option value="João Gote">João Gote</option>
                        <option value="Luzia Augusta">Luzia Augusta</option>
                        <option value="Metalúrgicos">Metalúrgicos</option>
                        <option value="Minas Talco">Minas Talco</option>
                        <option value="Nova Serrana">Nova Serrana</option>
                        <option value="Novo Horizonte">Novo Horizonte</option>
                        <option value="Olaria">Olaria</option>
                        <option value="Pioneiros">Pioneiros</option>
                        <option value="São Francisco">São Francisco</option>
                        <option value="Serra Verde">Serra Verde</option>
                        <option value="Siderurgia">Siderurgia</option>
                        <option value="Soledade">Soledade</option>
                        <option value="Tiradentes">Tiradentes</option>
                        <option value="Vale do Engenho">Vale do Engenho</option>
                        //-->
                    </select><br><br>
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