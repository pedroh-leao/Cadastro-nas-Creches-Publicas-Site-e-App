<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

//checa se o tipo de usuario que esta tentando acessar a página tem a permissão
include_once("isFuncionario.php");

include_once("conexao.php");
$pesquisa = $conn->real_escape_string($_POST['pesquisa']);
$cpfPesquisa = $conn->real_escape_string($_POST['cpfPesquisa']);
$pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$qtd_result_pg = filter_input(INPUT_POST, 'qtd_result_pg', FILTER_SANITIZE_NUMBER_INT);
//echo $pagina;
//echo $qtd_result_pg;
$inicio = ($pagina * $qtd_result_pg) - $qtd_result_pg;

$sql = "SELECT * FROM tb_responsavel where nome like '%$pesquisa%' and Cpf 
like '%$cpfPesquisa%' order by Nome LIMIT $inicio, $qtd_result_pg";

//echo $sql;
//executar o comando
$dadosResponsavel = $conn->query($sql);

//se número de registro retornados for maior que 0
if ($dadosResponsavel->num_rows > 0) {
?>
    <table>
        <tr>
            <th>Nome</th>
            <th>Nascimento</th>
            <th>Email</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Bairro</th>
            <th>Editar</th>
            <th>Excluir</th>
        </tr>
        <?php
        while ($exibir = $dadosResponsavel->fetch_assoc()) {
        ?>
            <tr>
                <td> <?php echo $exibir["Nome"] ?></td>
                <?php $data = $exibir["data_de_nascimento"] ?>
                <td> <?php
                        echo date('d/m/Y', strtotime($data));
                        ?>
                </td>
                <td> <?php echo $exibir["e_mail"] ?></td>
                <td> <?php echo $exibir["Cpf"] ?></td>
                <td> <?php echo $exibir["telefone"] ?></td>
                <td> <?php echo $exibir["endereco"] ?></td>
                <?php
                //busca o bairro na tb_bairro de acordo com o id salvo na tb_responsavel
                $sqlBairro = "SELECT * FROM tb_bairro WHERE id = " . $exibir["tb_bairro_id"];
                $dadosBairro = $conn->query($sqlBairro);
                $bairro = $dadosBairro->fetch_assoc();
                ?>
                <td><?php echo $bairro["nome"] ?> </td>

                <td><a class="botaoEditar" href="RUDresponsavel.php?Cpf=<?php echo $exibir["Cpf"] ?>">Editar</a></td>
                <td> <a class="botaoExcluir" href="#" onclick="confirmarExclusao(
                            '<?php echo $exibir["Cpf"] ?>')">Excluir </a>
                </td>

            </tr>
        <?php
        }
        ?>
    </table>



<?php
    //criando os links de paginação
    //conta quantos registros tem na tabela de pessoa
    $sql_qtd_registros = "SELECT COUNT(Cpf) as num_registros FROM tb_responsavel
                        where Nome like '%$pesquisa%' and Cpf like '%$cpfPesquisa%' ";
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