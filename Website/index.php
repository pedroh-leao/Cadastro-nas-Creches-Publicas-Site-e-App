<head>
    <html lang="pt-br">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro nas Creches</title>
    <link rel="stylesheet" href="estilo.css">


    <style>
        #menuInicial ul li:last-child a {
            background-color: rgb(24, 139, 233);
            border-radius: 2px;
        }

        #botaoCadastrarMenuInicial {
            float: right;
            background-color:
                rgb(24, 139, 233);
            margin-left: 4px;
        }

        .titleSite b {
            font-family: Calibri;
            color: rgb(16, 101, 172);
        }
    </style>

</head>

<body>

    <?php
    //menu de navegação
    include_once("navegacaoInicial.html");

    include_once("conexao.php");
    ?>
    <br><br><br>
    <h1 class="titleSite">Faça <b>Cadastro nas Creches</b> Agora</h1>

    <p class="titleSite" style="font-size: 20px;">Tenha praticidade para cadastrar seu filho em uma creche municipal.</p>
    <br><br><br>

    <button id="botaoCadastro" onclick="changeLog()">Entrar</button>
    <button id="botaoCadastro" onclick="changeCad()">Cadastrar</button>

    <br><br><br><br><br>
    <div id="informacoes" ><br><br><br><br><br><br>
        <h2 class="info">Veja dados do periodo de matricula:</h2><br>
    </div>
    <div id="columnchart_material" class="grafico"></div>

    <br><br><br><br>

    <div id="sobre" class="divSobre"><br><br>
        <h3>Uma forma fácil, rápida e totalmente segura para fazer cadastro de crianças nas creches!</h3><br>
        <h3>Cadastre seu filho e acompanhe com total transparência seu cadastro!</h3><br><br><br>
        <h3>Projeto feito pelos alunos João Pedro Figueiredo Tinoco Aniceto e Pedro Henrique Rabelo Leão de Oliveira,
            do terceiro ano do ensino médio do curso de informática do IFMG campus Ouro Branco, juntamente com a assistência
            dos Professores Carlos Eduardo Paulino, Márcio Assis Miranda e Saulo Henrique Cabral.</h3><br>
        <h3>O projeto tem como objetivo facilitar o cadastro de crianças nas creches de Ouro Branco por meio de um
            software, e nasceu da demanda da secretaria de educação de organizar, desenvolver e manter o sistema
            de ensino funcionando, de forma que consigam integrar às políticas e planos educacionais da União e do Estado
            nos termos da Lei de Diretrizes e Base da Educação Nacional. </h3><br>
        <h3>Tivemos a criação de um site onde é feito o cadastro de novas crianças nas creches, colocando dados da
            criança e do respectivo responsável, salvando-os e podendo alterá-los a qualquer momento, tendo também uma
            fila de espera caso as vagas para a creche de sua região estejam esgotadas. </h3><br>
        <h3>Os funcionários da secretaria ficam responsáveis pelo cadastro das creches, das vagas, dos períodos de
            matrícula e do zoneamento das creches, tais quais podem ser alterados quando for preciso, além de poder ver
            e alterar os dados dos responsáveis e suas respectivas crianças caso necessário. </h3><br>
        <h3>E além do site, no projeto também é incluso um aplicativo android de uso exclusivo do responsável onde
            ele pode ver suas notificações caso haja alguma remoção na fila de espera, confirmação ou cancelamento de
            matrícula ou de reserva de vaga. </h3><br>
        <h3>Com esse projeto esperamos ajudar no controle e no cadastro de crianças nas creches de Ouro Branco,
            facilitando esse cadastro e ajudando na visualização de dados. </h3><br>
    </div>

    <br><br><br><br><br><br><br><br><br><br>


    <div class="DivNomes">
        <h3>João Pedro Figueiredo Tinoco Aniceto</h3><br>
        <h3>Pedro Henrique Rabelo Leão de Oliveira</h3><br><br><br>
        <h3>Instituto Federal de Minas Gerais - Campus Ouro Branco</h3>
    </div>


    <br><br><br><br><br><br><br><br><br><br>


</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Creches', 'Total de Vagas', 'Vagas Disponíveis', 'Vagas Cadastradas'],
            <?php
            date_default_timezone_set('America/Sao_Paulo');
            // horário atual
            $horarioAtual = date('H:i:s');

            // Data atual
            $dataAtual = date("Y-m-d");

            // Explode o traço e retorna três arrays 
            $dataAtual = explode("-", $dataAtual);

            // Cria três variáveis $dia $mes $ano 
            list($anoAtual, $mesAtual, $diaAtual) = $dataAtual;

            $sqlData = "SELECT id, data_inicio, data_fim, hora_inicio, hora_fim FROM tb_periodo_cadastro;";
            $dadosPeriodos = $conn->query($sqlData);

            if ($dadosPeriodos->num_rows > 0) {
                while ($exibir = $dadosPeriodos->fetch_assoc()) {
                    $dataInicio = $exibir["data_inicio"]; // Data inicial do período
                    $dataInicio = explode("-", $dataInicio); // Explode o traço e retorna três arrays 
                    list($anoInicio, $mesInicio, $diaInicio) = $dataInicio; // Cria três variáveis com dia, mes e ano 

                    $dataFim = $exibir["data_fim"]; // Data final do período
                    $dataFim = explode("-", $dataFim); // Explode o traço e retorna três arrays 
                    list($anoFim, $mesFim, $diaFim) = $dataFim; // Cria três variáveis com dia, mes e ano

                    if ($anoAtual == $anoInicio && $mesAtual == $mesInicio && $diaAtual == $diaInicio) {
                        if ($horarioAtual >= $exibir['hora_inicio']) {
                            $retorno = $exibir["id"];
                        }
                    } elseif ($anoAtual == $anoFim && $mesAtual == $mesFim && $diaAtual == $diaFim) {
                        if ($horarioAtual <= $exibir['hora_fim']) {
                            $retorno = $exibir["id"];
                        }
                    } elseif ($anoAtual >= $anoInicio && $mesAtual >= $mesInicio && $diaAtual >= $diaInicio) {
                        if ($anoAtual <= $anoFim && $mesAtual <= $mesFim && $diaAtual <= $diaFim) {
                            $retorno = $exibir["id"];
                        }
                    }
                }
            }

            $sql = "SELECT * FROM tb_periodo_cadastro_tb_creche WHERE tb_periodo_cadastro_id = ' $retorno' order by  tb_creche_id";

            $dadosVagas = $conn->query($sql);

            if ($dadosVagas->num_rows > 0) {
                while ($exibir = $dadosVagas->fetch_assoc()) {
                    $vagasTotais = $exibir['numVagas'];
                    $vagasDisponiveis = $exibir['vagasDisponiveis'];
                    $vagasPreenchidas = $vagasTotais - $vagasDisponiveis;
                    $sqlNome = "SELECT nome FROM tb_creche where id =" . $exibir["tb_creche_id"];
                    $nomeCreche = $conn->query($sqlNome);
                    $exibirNome = $nomeCreche->fetch_assoc();
                    $nome = $exibirNome["nome"];


            ?>

                    ['<?php echo $nome ?>', <?php echo $vagasTotais ?>, <?php echo $vagasDisponiveis ?>, <?php echo $vagasPreenchidas ?>], <?php } ?>
            ]);


    <?php
            }


            $sqlBuscaPeriodo = "SELECT data_inicio, data_fim FROM tb_periodo_cadastro WHERE id = $retorno";
            $retornoPeriodo = $conn->query($sqlBuscaPeriodo);
            $rowPeriodo = $retornoPeriodo->fetch_assoc();
    ?>
    var options = {
        chart: {
            title: 'Vagas nas creches disponíveis',
            subtitle: 'Período de matrícula atual: <?php echo date('d/m/Y', strtotime($rowPeriodo["data_inicio"])) . " - " . date('d/m/Y', strtotime($rowPeriodo["data_fim"])); ?>',
            //backgroundColor: '#000'
        },
        chartArea: {
            backgroundColor: 'transparent'
        },
        backgroundColor: 'transparent',
        bars: 'vertical',
        titleTextStyle: {
            color: '#FFF',
            bold: true
        },
        vAxis: {
            format: 'decimal'
        },
        colors: ['#1e83e8', '#02d951', '#f01a1a']

    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, google.charts.Bar.convertOptions(options));


    }

    function changeCad() {
        window.location = "tipoDeCadastro.php";
    }

    function changeLog() {
        window.location = "tipoDeLogin.php";
    }
</script>

</html>