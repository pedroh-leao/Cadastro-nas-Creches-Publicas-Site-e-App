<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

include_once("conexao.php");

//$sqlCreche = "SELECT * FROM tb_creche where nome like '%$pesquisa%' order by nome LIMIT $inicio, $qtd_result_pg";
//$retornoCreche = $conn->query($sqlCreche);

if ($_SESSION['tipoUsuario'] == "funcionario") {
    $pesquisa = $conn->real_escape_string($_POST['pesquisa']);
    $pesquisaPeriodo = $conn->real_escape_string($_POST['pesquisaPeriodo']);
    //$pesquisaResponsavel = $conn->real_escape_string($_POST['pesquisaResponsavel']);
    $pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    $qtd_result_pg = filter_input(INPUT_POST, 'qtd_result_pg', FILTER_SANITIZE_NUMBER_INT);
    //echo $pagina;
    //echo $qtd_result_pg;
    $inicio = ($pagina * $qtd_result_pg) - $qtd_result_pg;

    //criar o comando sql
    if ($pesquisa == "1" && $pesquisaPeriodo == "1") {
        $sql = "SELECT * FROM tb_cadastro ORDER BY posicaoFila LIMIT $inicio, $qtd_result_pg";
    } elseif ($pesquisa == "1" && $pesquisaPeriodo != "1") {
        $sql = "SELECT * FROM tb_cadastro where tb_periodo_cadastro_id = $pesquisaPeriodo  ORDER BY posicaoFila LIMIT $inicio, $qtd_result_pg";
    } elseif ($pesquisa != "1" && $pesquisaPeriodo == "1") {
        $sql = "SELECT * FROM tb_cadastro where tb_creche_id = $pesquisa ORDER BY posicaoFila LIMIT $inicio, $qtd_result_pg";
    } else {
        $sql = "SELECT * FROM tb_cadastro where tb_periodo_cadastro_id = $pesquisaPeriodo 
        and tb_creche_id = $pesquisa ORDER BY posicaoFila LIMIT $inicio, $qtd_result_pg";
    }

    $retornoBusca = $conn->query($sql);

    if ($retornoBusca->num_rows > 0) {
?>
        <table>
            <tr>
                <th>Criança</th>
                <th>Creche</th>
                <th>Cpf do responsável</th>
                <th>Status</th>
                <th>Período</th>
                <th>Data/hora do envio</th>
                <th>Cancelar</th>
            </tr>
            <?php
            while ($exibir = $retornoBusca->fetch_assoc()) {
            ?>
                <tr>
                    <?php
                    $sqlCrianca = "SELECT * FROM tb_crianca WHERE id = " . $exibir["tb_crianca_id"];
                    $retornoCrianca = $conn->query($sqlCrianca);
                    $crianca = $retornoCrianca->fetch_assoc();
                    ?>
                    <td><?php echo $crianca["nome"] ?></td>

                    <?php
                    $sqlCreche = "SELECT nome FROM tb_creche WHERE id = " . $exibir["tb_creche_id"];
                    $retornoCreche = $conn->query($sqlCreche);
                    $nomeCreche = $retornoCreche->fetch_assoc();
                    ?>
                    <td> <?php echo $nomeCreche["nome"] ?></td>

                    <td><?php echo $crianca["tb_responsavel_Cpf"] ?></td>

                    <?php
                    if ($exibir["matriculado"] == 0) {
                    ?>
                        <td><?php echo "Na fila de espera"; ?></td>
                    <?php
                    } elseif ($exibir["isReserva"] == 1) {
                    ?>
                        <td><?php echo "Vaga reservada"; ?></td>
                    <?php
                    } else {
                    ?>
                        <td><?php echo "Matriculado"; ?></td>
                    <?php
                    }
                    ?>

                    <?php
                    $sqlPeriodo = "SELECT data_inicio, data_fim FROM tb_periodo_cadastro WHERE id = " . $exibir["tb_periodo_cadastro_id"];
                    $retornoPeriodo = $conn->query($sqlPeriodo);
                    $rowPeriodo = $retornoPeriodo->fetch_assoc();
                    ?>
                    <td><?php echo date('d/m/Y', strtotime($rowPeriodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($rowPeriodo["data_fim"])) ?></td>

                    <td><?php echo date('d/m/Y H:i', strtotime($exibir["data_hora"])) ?></td>

                    <td> <a class="botaoExcluir" href="#" onclick="cancelarSolicitacaoFuncionario(
                        '<?php echo $exibir["matriculado"] ?>',
                        '<?php echo $exibir["tb_crianca_id"] ?>',
                        '<?php echo $exibir["tb_creche_id"] ?>',
                        '<?php echo $exibir["tb_periodo_cadastro_id"] ?>',
                        '<?php echo $exibir["isReserva"] ?>')">Cancelar</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>

        <?php
        //criando os links de paginação
        //conta quantos registros tem na tabela de pessoa
        if ($pesquisa == "1" && $pesquisaPeriodo == "1") {
            $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro";
        } elseif ($pesquisa == "1" && $pesquisaPeriodo != "1") {
            $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro where tb_periodo_cadastro_id = $pesquisaPeriodo";
        } elseif ($pesquisa != "1" && $pesquisaPeriodo == "1") {
            $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro where tb_creche_id = $pesquisa";
        } else {
            $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro 
            where tb_periodo_cadastro_id = $pesquisaPeriodo 
            and tb_creche_id = $pesquisa";
        }
        //echo $sql_qtd_registros;

        $result_registros = $conn->query($sql_qtd_registros);
        $qtd_registros = $result_registros->fetch_assoc();

        //quantidade de página
        //a função ceil() pega o maior número 
        $qtd_paginas = ceil($qtd_registros["num_registros"] / $qtd_result_pg);

        //limitar a quantidade de links
        $max_links = 2;

        //link para a primeira página
        echo "<nav style='border-radius: 7.5px; width: 99.25%;' aria-label='Paginação de registros'>";
        echo "<ul class='pagination'>";

        echo " <li class='page-item'><a href='#'  class='page-link' onclick='listar_registros(1, $qtd_result_pg)'>Primeira</a></li>";

        for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
            if ($pag_ant >= 1) {
                echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($pag_ant, $qtd_result_pg)'> $pag_ant </a></li>";
            }
        }

        echo "<li class='page-link text-dark'> $pagina </li> "; //escreve a página atual

        for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
            if ($pag_dep <= $qtd_paginas) {
                echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($pag_dep, $qtd_result_pg)'> $pag_dep </a></li>";
            }
        }

        //link para a última página
        echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($qtd_paginas, $qtd_result_pg)'>Última</a></li>";
        echo "</ul></nav>";
    } else {
        echo "<h4>Nenhum registro retornado!</h4>";
    }
} else {
    $cpf_resp = $_SESSION['cpf'];

    $pesquisaResponsavel = $conn->real_escape_string($_POST['pesquisaResponsavel']);
    $pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
    $qtd_result_pg = filter_input(INPUT_POST, 'qtd_result_pg', FILTER_SANITIZE_NUMBER_INT);
    //echo $pagina;
    //echo $qtd_result_pg;
    $inicio = ($pagina * $qtd_result_pg) - $qtd_result_pg;
    //criar o comando sql
    $sql = "SELECT id, nome
        FROM tb_crianca
        WHERE tb_responsavel_Cpf = '$cpf_resp' and nome like '%$pesquisaResponsavel%' 
        ORDER BY nome";

    $registrosResponsavel = 0;
    //executar o comando sql
    $dadosCrianca = $conn->query($sql);

    //se o numero de registro for maior que 0
    if ($dadosCrianca->num_rows > 0) {
        while ($rowCrianca = $dadosCrianca->fetch_assoc()) {
            $sqlCadastro = "SELECT * FROM tb_cadastro WHERE tb_crianca_id = " . $rowCrianca["id"];
            $retornoBusca = $conn->query($sqlCadastro);



            if ($retornoBusca->num_rows > 0) {
                $haRetornos = 1;
        ?>
                <br><br><br>
                <table>
                    <tr>
                        <th>Criança</th>
                        <th>Creche</th>
                        <th>Status</th>
                        <th>Período</th>
                        <th>Data/hora que foi feita</th>
                        <th>Cancelar</th>
                    </tr>
                    <?php
                    break;
                } else {
                    $haRetornos = 0;
                }
            }
            if ($haRetornos == 1) {
                $dadosCrianca = $conn->query($sql);
                while ($rowCrianca = $dadosCrianca->fetch_assoc()) {
                    $sqlCadastro = "SELECT * FROM tb_cadastro WHERE tb_crianca_id = " . $rowCrianca["id"] . " LIMIT $inicio, $qtd_result_pg";
                    $retornoBusca = $conn->query($sqlCadastro);
                    while ($exibir = $retornoBusca->fetch_assoc()) {
                        $registrosResponsavel++;
                    ?>
                        <tr>
                            <td> <?php echo $rowCrianca["nome"] ?></td>

                            <?php
                            $sqlCreche = "SELECT nome FROM tb_creche WHERE id = " . $exibir["tb_creche_id"];
                            $retornoCreche = $conn->query($sqlCreche);
                            $nomeCreche = $retornoCreche->fetch_assoc();
                            ?>
                            <td> <?php echo $nomeCreche["nome"] ?></td>

                            <?php
                            if ($exibir["matriculado"] == 0) {
                            ?>
                                <td><?php echo "Na fila de espera"; ?></td>
                            <?php
                            } elseif ($exibir["isReserva"] == 1) {
                            ?>
                                <td><?php echo "Vaga reservada"; ?></td>
                            <?php
                            } else {
                            ?>
                                <td><?php echo "Matriculado"; ?></td>
                            <?php
                            }
                            ?>

                            <?php
                            $sqlPeriodo = "SELECT data_inicio, data_fim FROM tb_periodo_cadastro WHERE id = " . $exibir["tb_periodo_cadastro_id"];
                            $retornoPeriodo = $conn->query($sqlPeriodo);
                            $rowPeriodo = $retornoPeriodo->fetch_assoc();
                            ?>
                            <td><?php echo date('d/m/Y', strtotime($rowPeriodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($rowPeriodo["data_fim"])) ?></td>

                            <td><?php echo date('d/m/Y H:i', strtotime($exibir["data_hora"])) ?></td>

                            <td> <a class="botaoExcluir" href="#" onclick="cancelarSolicitacaoResponsavel(
                                '<?php echo $exibir["matriculado"] ?>',
                                '<?php echo $exibir["tb_crianca_id"] ?>',
                                '<?php echo $exibir["tb_creche_id"] ?>',
                                '<?php echo $exibir["tb_periodo_cadastro_id"] ?>',
                                '<?php echo $exibir["isReserva"] ?>')">Cancelar</a>
                            </td>
                        </tr>

                <?php
                    }
                }
                ?>
                </table>
    <?php
                //criando os links de paginação
                //conta quantos registros tem na tabela de pessoa
                //$sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro WHERE nome like '%$pesquisaResponsavel%' ";
                //echo $sql_qtd_registros;

                //$result_registros = $conn->query($sql_qtd_registros);
                //$qtd_registros = $result_registros->fetch_assoc();

                //echo $registrosResponsavel;
                //quantidade de página
                //a função ceil() pega o maior número 
                $qtd_paginas = ceil($registrosResponsavel / $qtd_result_pg);

                //limitar a quantidade de links
                $max_links = 2;

                //link para a primeira página
                echo "<nav style='border-radius: 7.5px; width: 99.25%;' aria-label='Paginação de registros'>";
                echo "<ul class='pagination'>";

                echo " <li class='page-item'><a href='#'  class='page-link' onclick='listar_registros(1, $qtd_result_pg)'>Primeira</a></li>";

                for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
                    if ($pag_ant >= 1) {
                        echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($pag_ant, $qtd_result_pg)'> $pag_ant </a></li>";
                    }
                }

                echo "<li class='page-link text-dark'> $pagina </li> "; //escreve a página atual

                for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
                    if ($pag_dep <= $qtd_paginas) {
                        echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($pag_dep, $qtd_result_pg)'> $pag_dep </a></li>";
                    }
                }

                //link para a última página
                echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($qtd_paginas, $qtd_result_pg)'>Última</a></li>";
                echo "</ul></nav>";
            } else {
                echo "<h4>Nenhuma solicitação de matrícula feita!</h4>";
            }
        } else {
            echo "<h4>Nenhuma criança cadastrada no site!</h4>";
        }
    }
    ?>

    <br><br><br><br><br>
    <?php






    /*else {
    $cpf_resp = $_SESSION['cpf'];
    //criar o comando sql
    $sql = "SELECT id, nome
        FROM tb_crianca
        WHERE tb_responsavel_Cpf = '$cpf_resp' AND nome LIKE '%$pesquisa%'
        ORDER BY nome";

    //executar o comando sql
    $dadosCrianca = $conn->query($sql);

    //se o numero de registro for maior que 0
    if ($dadosCrianca->num_rows > 0) {
        while ($rowCrianca = $dadosCrianca->fetch_assoc()) {
            $sqlCadastro = "SELECT * FROM tb_cadastro WHERE tb_crianca_id = " . $rowCrianca["id"];
            $retornoBusca = $conn->query($sqlCadastro);
            if ($retornoBusca->num_rows > 0) {
                $haRetornos = 1;
        ?>
                <br><br><br>
                <table>
                    <tr>
                        <th>Criança</th>
                        <th>Creche</th>
                        <th>Status</th>
                        <th>Período</th>
                        <th>Data/hora que foi feita</th>
                        <th>Cancelar</th>
                    </tr>
                    <?php
                    break;
                } else {
                    $haRetornos = 0;
                }
            
            if ($haRetornos == 1) {
                $dadosCrianca = $conn->query($sql);
                while ($rowCrianca = $dadosCrianca->fetch_assoc()) {
                    $sqlCadastro = "SELECT * FROM tb_cadastro WHERE tb_crianca_id = " . $rowCrianca["id"];
                    $retornoBusca = $conn->query($sqlCadastro);
                    while ($exibir = $retornoBusca->fetch_assoc()) {
                    ?>
                        <tr>
                            <td> <?php echo $rowCrianca["nome"] ?></td>

                            <?php
                            $sqlCreche = "SELECT nome FROM tb_creche WHERE id = " . $exibir["tb_creche_id"];
                            $retornoCreche = $conn->query($sqlCreche);
                            $nomeCreche = $retornoCreche->fetch_assoc();
                            ?>
                            <td> <?php echo $nomeCreche["nome"] ?></td>

                            <?php
                            if ($exibir["matriculado"] == 0) {
                            ?>
                                <td><?php echo "Na fila de espera"; ?></td>
                            <?php
                            } elseif ($exibir["isReserva"] == 1) {
                            ?>
                                <td><?php echo "Vaga reservada"; ?></td>
                            <?php
                            } else {
                            ?>
                                <td><?php echo "Matriculado"; ?></td>
                            <?php
                            }
                            ?>

                            <?php
                            $sqlPeriodo = "SELECT data_inicio, data_fim FROM tb_periodo_cadastro WHERE id = " . $exibir["tb_periodo_cadastro_id"];
                            $retornoPeriodo = $conn->query($sqlPeriodo);
                            $rowPeriodo = $retornoPeriodo->fetch_assoc();
                            ?>
                            <td><?php echo date('d/m/Y', strtotime($rowPeriodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($rowPeriodo["data_fim"])) ?></td>

                            <td><?php echo date('d/m/Y H:i', strtotime($exibir["data_hora"])) ?></td>

                            <td> <a a href="#" onclick="cancelarSolicitacaoResponsavel(
                                '<?php echo $exibir["matriculado"] ?>',
                                '<?php echo $exibir["tb_crianca_id"] ?>',
                                '<?php echo $exibir["tb_creche_id"] ?>',
                                '<?php echo $exibir["tb_periodo_cadastro_id"] ?>',
                                '<?php echo $exibir["isReserva"] ?>')">Cancelar</a>
                            </td>
                        </tr>

                <?php
                    }
                }
                
                ?>
                </table>
                <?php
                    //criando os links de paginação
                    //conta quantos registros tem na tabela de pessoa
                    if($pesquisa == "1" && $pesquisaPeriodo == "1"){
                        $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro";
                    }elseif($pesquisa == "1" && $pesquisaPeriodo != "1"){
                        $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro where tb_creche_id = $pesquisa";
                    }elseif($pesquisa != "1" && $pesquisaPeriodo == "1"){
                        $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro where tb_periodo_cadastro_id = $pesquisaPeriodo";
                    }else{
                        $sql_qtd_registros = "SELECT COUNT(tb_crianca_id) as num_registros FROM tb_cadastro 
                        where tb_periodo_cadastro_id = $pesquisaPeriodo 
                        and tb_creche_id = $pesquisa";
                        
                    }
                    //echo $sql_qtd_registros;

                    $result_registros = $conn->query($sql_qtd_registros);
                    $qtd_registros = $result_registros->fetch_assoc();

                    //quantidade de página
                    //a função ceil() pega o maior número 
                    $qtd_paginas = ceil($qtd_registros["num_registros"] / $qtd_result_pg);

                    //limitar a quantidade de links
                    $max_links = 2;

                    //link para a primeira página
                    echo "<nav style='border-radius: 7.5px; width: 99.25%;' aria-label='Paginação de registros'>";
                    echo "<ul class='pagination'>";

                    echo " <li class='page-item'><a href='#'  class='page-link' onclick='listar_registros(1, $qtd_result_pg)'>Primeira</a></li>";

                    for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
                        if ($pag_ant >= 1) {
                            echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($pag_ant, $qtd_result_pg)'> $pag_ant </a></li>";
                        }
                    }

                    echo "<li class='page-link text-dark'> $pagina </li> "; //escreve a página atual

                    for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
                        if ($pag_dep <= $qtd_paginas) {
                            echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($pag_dep, $qtd_result_pg)'> $pag_dep </a></li>";
                        }
                    }

                    //link para a última página
                    echo "<li class='page-item'><a href='#'  class='page-link' onclick='listar_registros($qtd_paginas, $qtd_result_pg)'>Última</a></li>";
                    echo "</ul></nav>";
                } 
                
                     else {
                        echo "<h4>Nenhuma solicitação de matrícula feita!</h4>";
                    }
                } 
            }
            else {
                echo "<h4>Nenhuma criança cadastrada no site!</h4>";
            }
            */ ?>