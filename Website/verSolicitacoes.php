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


    <title>Solicitações de Matrícula</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="estilo.css">

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
        <h2 style="text-align: center">Solicitações de Matrícula</h2>
        <br><br><br>

        <?php
        if ($_SESSION['tipoUsuario'] == "funcionario") {
        ?>
            <form action="buscarSolicitacoes.php" method="POST" id="form-pesquisa">
                <label for="pesquisa"><strong>Selecione a creche que deseja pesquisar:</strong></label> <br>
                <select style="font-size:  18px;" name="pesquisa" id="pesquisa">
                    <?php
                    //criar o comando sql
                    $sqlPesquisaCreche = "SELECT id, nome FROM tb_creche ORDER BY nome";

                    //executar o comando sql
                    $crechesPesquisadas = $conn->query($sqlPesquisaCreche);
                    ?>
                    <option value="1">Qualquer</option>
                    <?php
                    while ($rowCrechePesq = $crechesPesquisadas->fetch_assoc()) {
                    ?>
                    
                        <option value="<?php echo $rowCrechePesq["id"]; ?>"><?php echo $rowCrechePesq["nome"]; ?></option>
                    <?php
                    }
                    ?>
                </select><br>
                <label for="pesquisaPeriodo"><strong>Selecione o período que deseja pesquisar:</strong></label><br>
                <select style="font-size:  18px;" name="pesquisaPeriodo" id="pesquisaPeriodo" selected="Selecione um periodo">
                    <?php
                    $sqlIdPeriodo = "SELECT tb_periodo_cadastro_id FROM tb_periodo_cadastro_tb_creche ORDER BY tb_periodo_cadastro_id";

                    //executar o comando sql
                    $idPeriodos = $conn->query($sqlIdPeriodo);
                    $comparaId = 0;

                    ?>
                    <option value="1" selected>Qualquer</option>
                    <?php

                    while ($rowIdPeriodos = $idPeriodos->fetch_assoc()) {
                        if ($rowIdPeriodos["tb_periodo_cadastro_id"] != $comparaId) {
                            $sqlPesquisaPeriodo = "SELECT * FROM tb_periodo_cadastro WHERE id = " . $rowIdPeriodos["tb_periodo_cadastro_id"];
                            $periodoPesquisa = $conn->query($sqlPesquisaPeriodo);
                            $rowPeriodos = $periodoPesquisa->fetch_assoc();
                    ?>
                            <option value="<?php echo $rowPeriodos["id"]; ?>">
                                <?php echo date('d/m/Y', strtotime($rowPeriodos["data_inicio"])) . " - " . date('d/m/Y', strtotime($rowPeriodos["data_fim"])); ?>
                            </option>
                    <?php
                        }
                        $comparaId = $rowIdPeriodos["tb_periodo_cadastro_id"];
                    }
                    ?>
                </select><br><br>
                <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button>
            </form>
            <br>
            
            <div class="resultados">
            </div>
        <?php
        } else {
        ?>
            <form action="buscarSolicitacoes.php" method="POST" id="form-pesquisa">
                <input type="text" name="pesquisaResponsavel" id="pesquisaResponsavel" class="inputPesquisa" placeholder="Nome da criança">
                <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button>
            </form>
            <div class="resultados">
            </div>
        <?php
        }
        ?>

        

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
        var pesquisaPeriodo = $("#pesquisaPeriodo").val();
        var pesquisaResponsavel = $("#pesquisaResponsavel").val();
        var dados = { //define o objeto com os dados a serem enviados
            pesquisa: pesquisa,
            pesquisaPeriodo: pesquisaPeriodo,
            pesquisaResponsavel: pesquisaResponsavel,
            pagina: pagina,
            qtd_result_pg: qtd_result_pg
        }

        $.post('buscarSolicitacoes.php', dados, function(retorna) { //envia os dados via post
            $(".resultados").html(retorna); //define onde o resultado será exibido
        });
    }
</script>
<script>
    function cancelarSolicitacaoResponsavel(matriculado, idCrianca, idCreche, idPeriodo, isReserva) {
        //o responsável pode excluir apenas a solicitação que não estiver "matriculada" ainda
        if (matriculado == 1) {
            swal('A solicitação não pode ser cancelada', 'Apenas solicitações na fila de espera podem ser canceladas por você! \nSolicite que um funcionário exclua esta solicitação.', 'error').then((value) => {

            });
        } else {
            swal({
                title: "Deseja realmente cancelar a solicitação?",
                text: "A solicitação de matrícula será cancelada!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location = "excluirMatricula.php?crianca=" + idCrianca + "&creche=" + idCreche + "&periodo=" + idPeriodo + "&matriculado=" + matriculado + "&isReserva=" + isReserva;
                };
            });
        }
    }

    function cancelarSolicitacaoFuncionario(matriculado, idCrianca, idCreche, idPeriodo, isReserva) {
        //o funcionário pode excluir tanto as solicitações que estão matriculadas quanto as que estão na fila de espera
        swal({
            title: "Deseja realmente cancelar a solicitação?",
            text: "A solicitação de matrícula será cancelada!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "excluirMatricula.php?crianca=" + idCrianca + "&creche=" + idCreche + "&periodo=" + idPeriodo + "&matriculado=" + matriculado + "&isReserva=" + isReserva;
            };
        });
    }
</script>

</html>