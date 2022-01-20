<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

//checa se o tipo de usuario que esta tentando acessar a página tem a permissão
include_once("isFuncionario.php");

include_once("conexao.php");

$pesquisa = $conn->real_escape_string($_POST['pesquisa']);
$pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$qtd_result_pg = filter_input(INPUT_POST, 'qtd_result_pg', FILTER_SANITIZE_NUMBER_INT);
//echo $pagina;
//echo $qtd_result_pg;
$inicio = ($pagina * $qtd_result_pg) - $qtd_result_pg;

$sql = "SELECT * FROM tb_funcionario_secretaria where nome like '%$pesquisa%' order by nome LIMIT $inicio, $qtd_result_pg";

//echo $sql;
//executar o comando
$dadosFuncionario = $conn->query($sql);

//se número de registro retornados for maior que 0
if ($dadosFuncionario->num_rows > 0) {
?>
    <table>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Email</th>
            <th>Cargo</th>
            <th>Autorizado</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
        <?php
        while ($exibir = $dadosFuncionario->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $exibir["nome"] ?></td>
                <td><?php echo $exibir["CPF"] ?> </td>
                <td><?php echo $exibir["e_mail"] ?></td>
                <td><?php echo $exibir["cargo_funcionario"] ?></td>

                <!-- verificar se o atributo autorizado está retornando 0(false) ou 1(true) -->
                <?php
                if ($exibir["autorizado"] == 0) {
                ?>
                    <div>
                        <td>
                            <span class="switch" id="switch">
                                <input id=<?php echo $exibir["CPF"] ?> type="checkbox" onclick="autenticacao('<?php echo $exibir["CPF"] ?>')" />
                                <label for=<?php echo $exibir["CPF"] ?> data-liga="On" data-desliga="Off"></label>
                            </span>
                        </td>
                    </div>
                <?php
                } else {
                ?>
                    <div>
                        <td>
                            <span class="switch" id="switch">
                                <input id=<?php echo $exibir["CPF"] ?> type="checkbox" checked onclick="autenticacao('<?php echo $exibir["CPF"] ?>')" />
                                <label for=<?php echo $exibir["CPF"] ?> data-liga="On" data-desliga="Off"></label>
                            </span>
                        </td>
                    </div>
                <?php
                }
                ?>

                <td><a class="botaoEditar" href="RUDfuncionario.php?CPF=<?php echo $exibir["CPF"] ?>">Editar</a></td>
                <td> <a class="botaoExcluir" href="#" onclick="confirmarExclusao(
                            '<?php echo $exibir["CPF"] ?>')">Excluir </a>
                </td>

            </tr>
        <?php
        }
        ?>
    </table>
<?php
    //criando os links de paginação
    //conta quantos registros tem na tabela de pessoa
    $sql_qtd_registros = "SELECT COUNT(CPF) as num_registros FROM tb_funcionario_secretaria
                            where nome like '%$pesquisa%'";
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