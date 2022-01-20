<?php
//adicionar um dropdown de períodos de matrícula para o responsável no verSolicitacoes.php e no buscaSolicitacoes.php


//cada creche possui uma fila de espera
?>

<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

include_once("conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Fila de espera</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="stylesheet" href="estilo.css">
    <style>
        #specificText {
            -webkit-text-stroke-width: 2px;
            -webkit-text-stroke-color: #000;
            font-size: 3em;
            color: #FFF;
            font-family: calibri;
        }
    </style>

</head>

<body>

    <?php
    /* mesmo que já existe essa verificação no topo da página, esse if apenas para evitar de vazar dados nos 
instantes de delay de carregamento de uma pagina para outra */
    if ($_SESSION['logged'] == true) {
        if ($_SESSION['tipoUsuario'] == "funcionario") {

            //menu de navegação
            include_once("navegacaoFuncionario.html");
        } else {

            //menu de navegação
            include_once("navegacaoResponsavel.html");
        }
    ?>

        <br><br>
        <h1 style="text-align: center">Fila de Espera</h1>
        <br><br><br>

        <?php
        if (isset($_POST["idCreche"])) {
            $idCreche = $_POST["idCreche"];
        } else {
            if (isset($_GET["idCreche"])) {
                $idCreche = $_GET["idCreche"];
            } else {
                $idCreche = "";
            }
        }
        if (isset($_POST["pesquisaCrianca"])) {
            $pesquisaCrianca = $_POST["pesquisaCrianca"];
        } else {
            $pesquisaCrianca = "";
        }

        if ($_SESSION['tipoUsuario'] == "funcionario") {
        ?>
            <form action="filaDeEspera.php" method="POST" id="form-pesquisa">

                <label for="idCreche"><strong>Selecione a creche que deseja pesquisar:</strong></label> <br><br>
                <select style="font-size:  18px;" name="idCreche" id="idCreche">
                    <option value="" disabled <?php echo ($idCreche == "") ? "selected" : "" ?>>-- selecione uma creche --</option>
                    <?php
                    //criar o comando sql
                    $sqlPesquisaCreche = "SELECT id, nome FROM tb_creche ORDER BY nome";

                    //executar o comando sql
                    $crechesPesquisadas = $conn->query($sqlPesquisaCreche);

                    while ($rowCrechePesq = $crechesPesquisadas->fetch_assoc()) {
                    ?>
                        <option value="<?php echo $rowCrechePesq["id"]; ?>" <?php echo ($idCreche == $rowCrechePesq["id"]) ? "selected" : "" ?>>
                            <?php echo $rowCrechePesq["nome"]; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
                <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>

                <input type="text" name="pesquisaCrianca" id="pesquisaCrianca" class="inputPesquisa" placeholder="Nome da criança" value="<?php echo $pesquisaCrianca; ?>">
                <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>

            </form>
        <?php
        } else {

            $cpfResponsavel = $_SESSION['cpf'];
            //buscar bairro do responsável para exibir fila de espera apenas da(s) creche(s) que atende(m) o bairro dele
            $sqlBuscaIdBairro = "SELECT tb_bairro_id FROM tb_responsavel WHERE Cpf = '$cpfResponsavel'";
            $retornoBuscaIdBairro = $conn->query($sqlBuscaIdBairro);
            $rowIdBairro = $retornoBuscaIdBairro->fetch_assoc();

            $sqlBuscaCreche = "SELECT tb_creche_id FROM tb_zona WHERE tb_bairro_id = " . $rowIdBairro["tb_bairro_id"];
            $retornoBuscaCreche = $conn->query($sqlBuscaCreche);

        ?>
            <form action="filaDeEspera.php" method="POST" id="form-pesquisa">

                <label for="idCreche"><strong>De qual creche deseja ver a fila de espera:</strong></label> <br><br>
                <select style="font-size:  18px;" name="idCreche" id="idCreche">
                    <option value="" disabled <?php echo ($idCreche == "") ? "selected" : "" ?>>-- selecione uma creche --</option>
                    <?php
                    while ($rowBuscaCreche = $retornoBuscaCreche->fetch_assoc()) {
                        //criar o comando sql
                        $sqlPesquisaCreche = "SELECT id, nome FROM tb_creche where id = " . $rowBuscaCreche["tb_creche_id"] . " ORDER BY nome";

                        //executar o comando sql
                        $crechesPesquisadas = $conn->query($sqlPesquisaCreche);

                        while ($rowCrechePesq = $crechesPesquisadas->fetch_assoc()) {
                    ?>
                            <option value="<?php echo $rowCrechePesq["id"]; ?>" <?php echo ($idCreche == $rowCrechePesq["id"]) ? "selected" : "" ?>>
                                <?php echo $rowCrechePesq["nome"]; ?>
                            </option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>

                <input type="text" name="pesquisaCrianca" id="pesquisaCrianca" class="inputPesquisa" placeholder="Nome da criança" value="<?php echo $pesquisaCrianca; ?>">
                <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>

            </form>
        <?php
        }

        include_once("verificaDataPeriodo.php"); //traz a variavel $retorno caso haja um período de matrícula ativo
        ?>

        <?php
        if ($retorno != 0) {

            if ($pesquisaCrianca != "" && $idCreche != "") {
                $sqlPesquisaCrianca = "SELECT id, nome FROM tb_crianca where nome like '%$pesquisaCrianca%' order by nome";
                $retornoPesquisaCrianca = $conn->query($sqlPesquisaCrianca);

                $sql = "SELECT * FROM tb_cadastro where matriculado = 0 and tb_creche_id = $idCreche and tb_periodo_cadastro_id = $retorno and (";
                $num_rows = $retornoPesquisaCrianca->num_rows;
                $contagem = 1;
                while ($rowCrianca = $retornoPesquisaCrianca->fetch_assoc()) {
                    if ($contagem != $num_rows) {
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " or";
                    } else {
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " )";
                    }
                    $contagem = $contagem + 1;
                }
            } elseif ($pesquisaCrianca == "" && $idCreche == "") {
                $sql = "SELECT * FROM tb_cadastro where matriculado = 0 and tb_periodo_cadastro_id = $retorno";
            } elseif ($idCreche != "") {
                $sql = "SELECT * FROM tb_cadastro where matriculado = 0 and tb_creche_id = $idCreche and tb_periodo_cadastro_id = $retorno";
            } elseif ($pesquisaCrianca != "") {
                $sqlPesquisaCrianca = "SELECT id, nome FROM tb_crianca where nome like '%$pesquisaCrianca%' order by nome";
                $retornoPesquisaCrianca = $conn->query($sqlPesquisaCrianca);

                $sql = "SELECT * FROM tb_cadastro where matriculado = 0 and tb_periodo_cadastro_id = $retorno and (";
                $num_rows = $retornoPesquisaCrianca->num_rows;
                $contagem = 1;
                while ($rowCrianca = $retornoPesquisaCrianca->fetch_assoc()) {
                    if ($contagem != $num_rows) {
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " or";
                    } else {
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " )";
                    }
                    $contagem = $contagem + 1;
                }
            }
            $sql = $sql . " ORDER BY posicaoFila";

            if ($_SESSION['tipoUsuario'] == "funcionario") {

                if ($idCreche != "") {
                    //executar o comando sql
                    $dadosCadastro = $conn->query($sql);

                    //se o numero de registro for maior que 0
                    if ($dadosCadastro->num_rows > 0) {
        ?>
                        <br><br><br>
                        <table class="tbFilaEspera">
                            <tr>
                                <th>Posição</th>
                                <th>Criança</th>
                                <th>Responsável</th>
                                <th>Cpf do responsável</th>
                                <th>Remover</th>
                            </tr>

                            <?php
                            while ($exibir = $dadosCadastro->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td class="posicaoFE"><?php echo $exibir["posicaoFila"] . "°" ?></td>

                                    <?php
                                    $sqlCrianca = "SELECT * FROM tb_crianca WHERE id = " . $exibir["tb_crianca_id"];
                                    $retornoCrianca = $conn->query($sqlCrianca);
                                    $crianca = $retornoCrianca->fetch_assoc();
                                    ?>
                                    <td><?php echo $crianca["nome"] ?></td>

                                    <?php
                                    $sqlResponsavelCrianca = "SELECT Nome FROM tb_responsavel WHERE Cpf = '" . $crianca["tb_responsavel_Cpf"] . "'";
                                    $retornoResponsavel = $conn->query($sqlResponsavelCrianca);
                                    $rowResponsavel = $retornoResponsavel->fetch_assoc();
                                    ?>
                                    <td><?php echo $rowResponsavel["Nome"] ?></td>

                                    <td><?php echo $crianca["tb_responsavel_Cpf"] ?></td>

                                    <td> <a class="botaoExcluir" href="#" onclick="cancelarSolicitacaoFuncionario(
                                    '1',
                                    '<?php echo $exibir["matriculado"] ?>',
                                    '<?php echo $exibir["tb_crianca_id"] ?>',
                                    '<?php echo $exibir["tb_creche_id"] ?>',
                                    '<?php echo $exibir["tb_periodo_cadastro_id"] ?>',
                                    '<?php echo $exibir["isReserva"] ?>')">Remover</a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    <?php
                    } else {
                    ?>
                    <br><br>
                        <div class="divTextoFila">
                            <h1>Não há ninguém na fila de espera para essa creche!</h1>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <br><br>
                    <div class="divTextoFila">
                        <h1>Selecione a creche da qual você deseja ver a fila de espera!</h1>
                    </div>
                    <?php
                }
            } else {
                if ($idCreche != "") {
                    //executar o comando sql
                    $dadosCadastro = $conn->query($sql);

                    //se o numero de registro for maior que 0
                    if ($dadosCadastro->num_rows > 0) {
                    ?>
                        <br><br><br>
                        <table class="tbFilaEspera">
                            <tr>
                                <th>Posição</th>
                                <th>Criança</th>
                                <th>Responsável</th>
                                <th>Cpf do responsável</th>
                            </tr>

                            <?php
                            while ($exibir = $dadosCadastro->fetch_assoc()) {

                                //essa parte foi passada para cá, pela comparacao que esta sendo feita para adicao da class "active-row"
                                $sqlCrianca = "SELECT * FROM tb_crianca WHERE id = " . $exibir["tb_crianca_id"];
                                $retornoCrianca = $conn->query($sqlCrianca);
                                $crianca = $retornoCrianca->fetch_assoc();
                            ?>
                                <tr <?php echo ($crianca["tb_responsavel_Cpf"] == $cpfResponsavel) ? "class='active-row'" : "" ?>>
                                    <td class="posicaoFE"><?php echo $exibir["posicaoFila"] . "°" ?></td>

                                    <?php
                                    /*$sqlCrianca = "SELECT * FROM tb_crianca WHERE id = " . $exibir["tb_crianca_id"];
                                    $retornoCrianca = $conn->query($sqlCrianca);
                                    $crianca = $retornoCrianca->fetch_assoc(); */
                                    ?>
                                    <td><?php echo $crianca["nome"] ?></td>

                                    <?php
                                    $sqlResponsavelCrianca = "SELECT Nome FROM tb_responsavel WHERE Cpf = '" . $crianca["tb_responsavel_Cpf"] . "'";
                                    $retornoResponsavel = $conn->query($sqlResponsavelCrianca);
                                    $rowResponsavel = $retornoResponsavel->fetch_assoc();
                                    ?>
                                    <td><?php echo $rowResponsavel["Nome"] ?></td>

                                    <?php
                                    if ($crianca["tb_responsavel_Cpf"] == $cpfResponsavel) {
                                    ?>
                                        <td><?php echo $crianca["tb_responsavel_Cpf"] ?></td>
                                    <?php
                                    } else {
                                        $novoCpf = substr($crianca["tb_responsavel_Cpf"], 3, 9);
                                        $novoCpf = "***" . $novoCpf . "**";
                                    ?>
                                        <td><?php echo $novoCpf ?></td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
            <?php
                    } else {
                        ?>
                    <br><br>
                        <div class="divTextoFila">
                            <h1>Não há ninguém na fila de espera para essa creche!</h1>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <br><br>
                    <div class="divTextoFila">
                        <h1>Selecione a creche da qual você deseja ver a fila de espera!</h1>
                    </div>
                    <?php
                }
            }
        } else {
            ?>

            <br><br><br>
            <h2><strong>Sem período de matrícula ativo nesse momento!</strong></h2>

        <?php
        }
        ?>

        <br><br><br><br><br>
    <?php
    }
    ?>
</body>

<script>
    function cancelarSolicitacaoFuncionario(qualTela, matriculado, idCrianca, idCreche, idPeriodo, isReserva) {
        swal({
            title: "Deseja realmente cancelar a solicitação?",
            text: "A solicitação de matrícula será cancelada!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "excluirMatricula.php?crianca=" + idCrianca + "&creche=" + idCreche + "&periodo=" + idPeriodo + "&matriculado=" + matriculado + "&isReserva=" + isReserva + "&qualTela=" + qualTela;
            };
        });
    }
</script>

</html>