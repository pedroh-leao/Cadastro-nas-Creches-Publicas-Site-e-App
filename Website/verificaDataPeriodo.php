<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");    
    
    $retorno = null; //0 = Sem período de cadastro ativo nesse momento
    $semCreche = false; //true = existe período de cadastro ativo naquele momento, porém não foi cadastrado um numero de vagas para aquela creche naquele período

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


    if($_SESSION['tipoUsuario'] == "responsavel"){
        
        //if abaixo é usado apenas para o arquivo solicitarMatricula.php
        //verifica se foram cadastradas vagas para aquela creche naquele período
        if($retorno != 0){
            $cpf_resp = $_SESSION['cpf'];
            $sqlResp = "SELECT tb_bairro_id FROM tb_responsavel WHERE Cpf = '$cpf_resp'";
            $idBairroResp = $conn->query($sqlResp);
            $rowBairro = $idBairroResp->fetch_assoc();

            $sqlIdCreche = "SELECT tb_creche_id FROM tb_zona WHERE tb_bairro_id = ".$rowBairro["tb_bairro_id"];
            $dadosIdCreche = $conn->query($sqlIdCreche);

            while($rowCreche = $dadosIdCreche->fetch_assoc()){
                $sqlVerificacao = "SELECT * FROM tb_periodo_cadastro_tb_creche WHERE tb_creche_id = " . $rowCreche["tb_creche_id"] . " AND tb_periodo_cadastro_id = $retorno";
                $dadosVerificacao = $conn->query($sqlVerificacao);
                if($dadosVerificacao->num_rows > 0){
                    $semCreche = false;
                    break;
                }else{
                    $semCreche = true;
                }
            }
        }
    }
?>