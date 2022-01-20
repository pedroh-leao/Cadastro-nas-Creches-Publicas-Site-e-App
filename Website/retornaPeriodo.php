<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");    
        
    $retorno = null; //0 = Sem período de cadastro ativo nesse momento

    date_default_timezone_set('America/Sao_Paulo');
    // horário atual
    $horarioAtual = date('H:i:s');    

    // Data atual
    $dataAtual = date("Y-m-d"); 

    // Explode o traço e retorna três arrays 
    $dataAtual = explode("-", $dataAtual); 

    // Cria três variáveis $dia $mes $ano 
    list($anoAtual, $mesAtual, $diaAtual) = $dataAtual;


    $sql = "SELECT id, data_inicio, data_fim, hora_inicio, hora_fim FROM tb_periodo_cadastro;";
    $dadosPeriodos = $conn->query($sql);

    if($dadosPeriodos->num_rows > 0){
        while ($exibir = $dadosPeriodos->fetch_assoc()){

            $dataInicio = $exibir["data_inicio"]; // Data inicial do período
            $dataInicio = explode("-", $dataInicio); // Explode o traço e retorna três arrays 
            list($anoInicio, $mesInicio, $diaInicio) = $dataInicio; // Cria três variáveis com dia, mes e ano 

            $dataFim = $exibir["data_fim"]; // Data final do período
            $dataFim = explode("-", $dataFim); // Explode o traço e retorna três arrays 
            list($anoFim, $mesFim, $diaFim) = $dataFim; // Cria três variáveis com dia, mes e ano
            
            if($anoAtual == $anoInicio && $mesAtual == $mesInicio && $diaAtual == $diaInicio){
                if($horarioAtual >= $exibir['hora_inicio']){
                    $retorno = $exibir["id"];
                }
            }
            elseif($anoAtual == $anoFim && $mesAtual == $mesFim && $diaAtual == $diaFim){
                if($horarioAtual<= $exibir['hora_fim']){
                    $retorno = $exibir["id"];
                }
            }elseif($anoAtual >= $anoInicio && $mesAtual >= $mesInicio && $diaAtual >= $diaInicio){
                if($anoAtual <= $anoFim && $mesAtual <= $mesFim && $diaAtual <= $diaFim){
                    $retorno = $exibir["id"];
                }
            }

        }
    }else{
        $retorno = 0;
    }

    if($retorno == null){
        $retorno = 0;
    }
?>