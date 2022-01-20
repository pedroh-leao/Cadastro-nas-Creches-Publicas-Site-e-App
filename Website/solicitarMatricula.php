<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isResponsavel.php");
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="estilo.css">
    <title>Solicitar Matrícula</title>
</head>

<body>

    <?php
        //menu de navegação
        include_once("navegacaoResponsavel.html");

        include_once("verificaDataPeriodo.php");
        if($retorno == 0){
            ?>

            <br><br><br>
            <h2><strong>Sem período de matrícula ativo nesse momento!</strong></h2>

            <?php
        }elseif($semCreche == true){
            ?>

            <br><br><br>
            <h2><strong>Não foram cadastradas vagas para as creches que atendem seu bairro no período de cadastro atual!</strong></h2>

            <?php        
        }else{
            ?>

            <div>
                <h1 id="titulo">Solicitar Matrícula da Criança</h1>
                <p id="subtitulo"> Complete as informações:</p><br>
            </div>
            <br>

            <form name="formSolicitaMatricula" action="inserirMatricula.php" method="POST">

                <fieldset>
                    <div>
                        <label for="filho"><strong>Criança:</strong></label><br>
                        <select name="filho" id="filho" >
                            <?php
                                //criar o comando sql
                                $sql = "SELECT *
                                FROM tb_crianca
                                WHERE tb_responsavel_Cpf = '$cpf_resp'
                                ORDER BY nome"; //variavel cpf_resp foi definida no arquivo "verificaDataPeriodo.php"
                    
                                //executar o comando sql
                                $dadosCrianca = $conn->query($sql);

                                while ($rowCrianca = $dadosCrianca->fetch_assoc()) { 
                                    ?>
                                    <option value="<?php echo $rowCrianca["id"]; ?>"><?php echo $rowCrianca["nome"]; ?></option>
                                    <?php
                                }
                                ?>
                        </select><br><br>
                    </div>
                    <div>
                        <label for="crecheNI"><strong>Creche:</strong></label><br>
                        <select name="crecheNI" id="crecheNI">
                            <?php
                                $dadosIdCreche = $conn->query($sqlIdCreche);

                                while($rowCreche = $dadosIdCreche->fetch_assoc()){
                                    $sqlCreche = "SELECT id, nome FROM tb_creche WHERE id = " . $rowCreche["tb_creche_id"];
                                    $dadosCreche = $conn->query($sqlCreche);

                                    while ($exibirCreche = $dadosCreche->fetch_assoc()) { 
                                        ?>
                                        <option value="<?php echo $exibirCreche["id"]; ?>"><?php echo $exibirCreche["nome"]; ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select><br><br>                        
                    </div>
                    <div>
                        <label for="idPer"><strong>Período de cadastro:</strong></label><br>
                        <select style="font-size:  18px;" name="idPer" id="idPer">
                        <?php
                            //incluir o bd
                            include_once('conexao.php');

                            //buscar dados do dropdown no BD(tb_periodo_cadastro)
                            //criar o comando sql
                            $sqlPeriodo = "SELECT data_inicio, data_fim FROM tb_periodo_cadastro WHERE id = ". $retorno;

                            //executar o comando sql
                            $periodos = $conn->query($sqlPeriodo);

                            while ($rowPeriodos = $periodos->fetch_assoc()) { 
                                ?>
                                <option value="<?php echo $retorno; ?>">
                                    <?php echo date('d/m/Y',strtotime($rowPeriodos["data_inicio"])) . " - " . date('d/m/Y',strtotime($rowPeriodos["data_fim"])); ?>
                                </option>
                                <?php
                            }
                            ?>
                    </select><br><br>
                    </div>
                </fieldset>

                <br>

                <div>
                    <button id="botaoCadastro" type="submit">Concluído</button>
                </div>

            </form>

            <?php
        }
    ?>
</body>
</html>