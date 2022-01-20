<?php
//esse arquivo já tem o session_start()
include_once("checkIsLogged.php");

//checa se o tipo de usuario que esta tentando acessar a página tem a permissão
include_once("isFuncionario.php");
?>

<head>
    <html lang="pt-br">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <?php
    //menu de navegação
    include_once("navegacaoFuncionario.html");

    include_once("retornaPeriodo.php");
    if($retorno == 0){
        ?>
        <br><br><br>
        <h1 class="titleSite" style="font-size: 2.75em;">Não há período de matrícula ativo no momento</h1><br>
        <?php
    }
    else{
        $sql = "SELECT * FROM tb_periodo_cadastro WHERE id = $retorno";
        $dadosSql = $conn->query($sql);
        $rowPeriodo = $dadosSql->fetch_assoc();
        ?>
        <br><br><br>
        <h1 class="titleSite" style="font-size: 2.75em;">Período de matrícula atual:</h1><br>

        <div class="divPeriodoAtual">
            <h2><?php echo date('d/m/Y', strtotime($rowPeriodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($rowPeriodo["data_fim"])); ?></h2>
        </div>
        <?php
    }
    ?>
    <br><br><br><br>
    <div>
        <h2>Dados dos Períodos de Matrícula</h2>
        <br>
        <button id="botaoMatricular1" type="button" onclick="trocarTela()">Visualizar</button>
    </div>

    <br><br><br><br><br><br>
</body>

<script>
    function trocarTela(){
        window.location = "selecionarNumVagas.php";
    }

    
</script>

</html>