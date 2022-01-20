<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");



include_once("conexao.php");
?>
<?php
$pesquisa = $conn->real_escape_string($_POST['pesquisa']);
$pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
$qtd_result_pg = filter_input(INPUT_POST, 'qtd_result_pg', FILTER_SANITIZE_NUMBER_INT);
//echo $pagina;
//echo $qtd_result_pg;
$inicio = ($pagina * $qtd_result_pg) - $qtd_result_pg;
if ($_SESSION['tipoUsuario'] == "funcionario") {
    //criar o comando sql
    $sql = "SELECT * FROM tb_crianca where nome like '%$pesquisa%' order by nome LIMIT $inicio, $qtd_result_pg";

    //executar o comando sql
    $dadosCrianca = $conn->query($sql);

    //se o numero de registro for maior que 0
    if ($dadosCrianca->num_rows > 0) {
?>

        <table>
            <tr>
                <th>Nome</th>
                <th>Responsável</th>
                <th>Data de nascimento</th>
                <th>Editar</th>
                <th>Excluir</th>
            </tr>

            <?php
            while ($exibir = $dadosCrianca->fetch_assoc()) {
            ?>
                <tr>

                    <td> <?php echo $exibir["nome"]   ?></td>

                    <?php
                    //busca o responsavel na tb_responsavel de acordo com o cpf salvo na tb_creche
                    $sqlResponsavel = "SELECT * FROM tb_responsavel WHERE Cpf = '$exibir[tb_responsavel_Cpf]'";
                    $dadosResponsavel = $conn->query($sqlResponsavel);
                    $responsavel = $dadosResponsavel->fetch_assoc();
                    ?>
                    <td><?php echo $responsavel["Nome"] ?> </td>

                    <?php $data = $exibir["data_de_nascimento"];   ?>
                    <td> <?php
                            echo date('d/m/Y', strtotime($data));
                            ?>
                    </td>
                    <td> <a class="botaoEditar" href="RUDcrianca.php?id=<?php echo $exibir["id"] ?>"> Editar </a></td>
                    <td> <a class="botaoExcluir" href="#" onclick="confirmarExclusao(
                            '<?php echo $exibir["id"] ?>',
                            '<?php echo $exibir["nome"] ?>')">Excluir </a>
                    </td>
                </tr>

            <?php
            }
            ?>
        </table>
        <?php
        //criando os links de paginação
        //conta quantos registros tem na tabela de pessoa
        $sql_qtd_registros = "SELECT COUNT(id) as num_registros FROM tb_crianca 
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
} else {
    $cpf_resp = $_SESSION['cpf'];
    //criar o comando sql
    $sql = "SELECT *
            FROM tb_crianca
            WHERE tb_responsavel_Cpf = '$cpf_resp'
            ORDER BY nome";

    //executar o comando sql
    $dadosCrianca = $conn->query($sql);

    //se o numero de registro for maior que 0
    if ($dadosCrianca->num_rows > 0) {
        ?>
        <br><br><br>
        <table>
            <tr>
                <th>Nome</th>
                <th>Data de nascimento</th>
                <th>Editar</th>
                <th>Excluir</th>
            </tr>

            <?php
            while ($exibir = $dadosCrianca->fetch_assoc()) {
            ?>
                <tr>
                    <td> <?php echo $exibir["nome"]   ?></td>
                    <?php $data = $exibir["data_de_nascimento"]   ?>
                    <td> <?php
                            echo date('d/m/Y', strtotime($data));
                            ?>
                    </td>

                    <td> <a class="botaoEditar" href="RUDcrianca.php?id=<?php echo $exibir["id"] ?>"> Editar </a></td>
                    <td> <a class="botaoExcluir" href="#" onclick="confirmarExclusao(
                            '<?php echo $exibir["id"] ?>',
                            '<?php echo $exibir["nome"] ?>')">Excluir </a>
                    </td>
                </tr>

    <?php
            }
        }
    }
    ?>
        </table>