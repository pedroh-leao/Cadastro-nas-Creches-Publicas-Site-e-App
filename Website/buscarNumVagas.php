<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

//checa se o tipo de usuario que esta tentando acessar a página tem a permissão
include_once("isFuncionario.php");

include_once("conexao.php");


include_once("conexao.php");
$pesquisa = $conn->real_escape_string($_POST['pesquisa']);
$pesquisaNome = $conn->real_escape_string($_POST['pesquisaNome']);
$pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$qtd_result_pg = filter_input(INPUT_POST, 'qtd_result_pg', FILTER_SANITIZE_NUMBER_INT);
//echo $pagina;
//echo $qtd_result_pg;
$registros = 1;
$inicio = ($pagina * $qtd_result_pg) - $qtd_result_pg;

if ($pesquisa == "1") {
    if ($pesquisaNome == "1") {
        $sql = "SELECT * FROM tb_periodo_cadastro_tb_creche order by tb_creche_id LIMIT $inicio, $qtd_result_pg ";
    } else {
        $sql = "SELECT * FROM tb_periodo_cadastro_tb_creche where tb_creche_id = '$pesquisaNome' 
            order by tb_creche_id LIMIT $inicio, $qtd_result_pg ";
    }
} else {
    if ($pesquisaNome == "1") {
        $sql = "SELECT * FROM tb_periodo_cadastro_tb_creche where tb_periodo_cadastro_id = $pesquisa 
                order by tb_creche_id LIMIT $inicio, $qtd_result_pg";
    } else {
        $sql = "SELECT * FROM tb_periodo_cadastro_tb_creche where tb_periodo_cadastro_id = $pesquisa 
                and tb_creche_id = '$pesquisaNome' order by tb_creche_id LIMIT $inicio, $qtd_result_pg ";
    }
}

//consultando a tabela de criancas para pegar o id com base no nome que o usuario digitou
//$sqlNome = "SELECT id FROM tb_creche where nome like '%$pesquisaNome%' order by nome ";
//echo $sqlNome;

$dadosFinal = $conn->query($sql);

if ($dadosFinal->num_rows > 0) {
?>
    <table>
        <tr>
            <th>Período de Matrícula</th>
            <th>Creche</th>
            <th>Vagas Totais</th>
            <th>Vagas Disponíveis</th>
            <th>Editar</th>
            <th>Excluir</th>

        </tr>
        <?php

        while ($exibir = $dadosFinal->fetch_assoc()) {
            $registros++;
        ?>
            <tr>
                <?php
                //buscar o período na tb_periodo_cadastro de acordo com o tb_periodo_cadastro_id
                $sqlPeriodo = "SELECT * FROM tb_periodo_cadastro WHERE id = " . $exibir["tb_periodo_cadastro_id"];
                $dadosPeriodo = $conn->query($sqlPeriodo);
                $periodo = $dadosPeriodo->fetch_assoc();
                ?>
                <td><?php echo date('d/m/Y', strtotime($periodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($periodo["data_fim"])); ?></td>

                <?php
                //buscar a creche na tb_creche de acordo com o tb_creche_id
                $sqlCreche = "SELECT * FROM tb_creche WHERE id = " . $exibir["tb_creche_id"];
                $dadosCreche = $conn->query($sqlCreche);
                $creche = $dadosCreche->fetch_assoc();
                ?>
                <td><?php echo $creche["nome"] ?></td>

                <td><?php echo $exibir["numVagas"] ?></td>
                <td><?php echo $exibir["vagasDisponiveis"] ?></td>

                <td>
                    <a class="botaoEditar" href="RUDnumVagas.php?tb_periodo_cadastro_id=<?php echo $exibir["tb_periodo_cadastro_id"] ?>&tb_creche_id=<?php echo $exibir["tb_creche_id"] ?> ">
                        Editar
                    </a>
                </td>
                <td>
                    <a class="botaoExcluir" href="#" onclick="confirmarExclusao(
                            '<?php echo date('d/m/Y', strtotime($periodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($periodo["data_fim"])) ?>',
                            '<?php echo $creche["nome"] ?>',
                            '<?php echo $exibir["tb_periodo_cadastro_id"] ?>',
                            '<?php echo $exibir["tb_creche_id"] ?>')">Excluir </a>
                </td>

            </tr>
        <?php


        }

        ?>
        <table>


        <?php
        if ($pesquisa == "1" && $pesquisaNome == "1") {
            $sql_qtd_registros = "SELECT COUNT(tb_creche_id) as num_registros FROM tb_periodo_cadastro_tb_creche";
        } elseif ($pesquisa == "1") {
            $sql_qtd_registros = "SELECT COUNT(tb_creche_id) as num_registros FROM tb_periodo_cadastro_tb_creche 
                              where tb_creche_id = $pesquisaNome";
        } elseif ($pesquisaNome == "1") {
            $sql_qtd_registros = "SELECT COUNT(tb_creche_id) as num_registros FROM tb_periodo_cadastro_tb_creche 
                              where tb_periodo_cadastro_id = $pesquisa ";
        } else {
            $sql_qtd_registros = "SELECT COUNT(tb_creche_id) as num_registros FROM tb_periodo_cadastro_tb_creche 
                              where tb_periodo_cadastro_id = $pesquisa and tb_creche_id = $pesquisaNome ";
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
        ?>