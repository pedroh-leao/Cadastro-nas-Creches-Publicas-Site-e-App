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
    <title>Lista de Creches</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="estilo.css">

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
        <h2 style="text-align: center">Lista de creches cadastradas</h2>
        <br><br><br>

        <form method="POST" id="form-pesquisa">
            <input type="text" name="pesquisa" id="pesquisa" class="inputPesquisa" placeholder="Nome da creche">
            <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>
        </form>
        <div class="resultados">
        </div>
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

        $.post('buscarCreche.php', dados, function(retorna) { //envia os dados via post
            $(".resultados").html(retorna); //define onde o resultado será exibido
        });
    }
</script>
<script>
    function confirmarExclusao(id, nome) {
        swal({
            title: "Deseja realmente excluir o registro?",
            text: "Você irá deletar a creche!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "excluirCreche.php?id=" + id;
            };
        });

    }
</script>


</html>