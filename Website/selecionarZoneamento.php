<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

//checa se o tipo de usuario que esta tentando acessar a página tem a permissão
include_once("isFuncionario.php");

include_once("conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de zoneamentos</title>

    <link rel="stylesheet" href="estilo.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <?php
    /* mesmo que já existe essa verificação no topo da página, esse if apenas para evitar de vazar dados nos 
    instantes de delay de carregamento de uma pagina para outra */
    if ($_SESSION['tipoUsuario'] == "funcionario") {
        //menu de navegação
        include_once("navegacaoFuncionario.html");
    ?>

        <br><br>
        <h2 style="text-align: center">Lista de zoneamentos cadastrados</h2>
        <br><br><br>

        <form action="buscarZoneamento.php" method="POST" id="form-pesquisa">

            <select style="font-size:  18px;" name="pesquisa" id="pesquisa" class="pesquisa">
                <?php

                $sqlIdCreche = "SELECT tb_creche_id FROM tb_zona ORDER BY tb_creche_id";
                //executar o comando sql
                $idCreche = $conn->query($sqlIdCreche);
                $comparaIdCreche = 0;
                ?>
                <option value="1" selected>Selecione uma Creche</option>
                <?php
                while ($rowIdCreches = $idCreche->fetch_assoc()) {
                    if ($rowIdCreches["tb_creche_id"] != $comparaIdCreche) {
                        $sqlPesquisaCreches = "SELECT * FROM tb_creche WHERE id = " . $rowIdCreches["tb_creche_id"];
                        $crechesPesquisa = $conn->query($sqlPesquisaCreches);
                        $rowCreches = $crechesPesquisa->fetch_assoc();
                ?>
                        <option value="<?php echo $rowCreches["id"]; ?>">
                            <?php echo $rowCreches["nome"] ?>
                        </option>
                <?php

                        $comparaIdCreche = $rowIdCreches["tb_creche_id"];
                    }
                }
                ?>
            </select><br><br>
            <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>


        </form>
        <div class="resultados">
        </div>



        <br><br><br><br><br>
    <?php
    }
    ?>
</body>
<script text="text/javascript">
    $(document).ready(function() { //executa assim que carrega a página
        //define as variáveis com a página atua
        var pagina = 1; // define a página atual
        var qtd_result_pg = 15; //define a quantidade de páginas por página

        listar_registros(pagina, qtd_result_pg); //chama a função listar_registros

        //chama a função assim que carrega a página
        $("#form-pesquisa").submit(function(evento) {
            evento.preventDefault();
            listar_registros(pagina, qtd_result_pg); //chama a função listar_registros
        });
    });

    function listar_registros(pagina, qtd_result_pg) {
        var pesquisa = $("#pesquisa").val();
        var dados = { //define o objeto com os dados a serem enviados
            pesquisa: pesquisa,
            pagina: pagina,
            qtd_result_pg: qtd_result_pg
        }

        $.post('buscarZoneamento.php', dados, function(retorna) { //envia os dados via post
            $(".resultados").html(retorna); //define onde o resultado será exibido
        });
    }
</script>
<script>
    function confirmarExclusao(idCreche, creche, bairro, idBairro) {
        swal({
            title: "Deseja realmente excluir o registro?",
            text: "Você irá deletar o zoneamento selecionado!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "excluirZoneamento.php?idBairro=" + idBairro;
            };
        });

    }
</script>

</html>