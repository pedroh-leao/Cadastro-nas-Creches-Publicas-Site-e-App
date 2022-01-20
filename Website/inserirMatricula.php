<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroSalvo(){
            swal('Sucesso' ,'Uma vaga na creche foi reservada para seu filho! \nAguarde o contato que será feito com você para finalizar a matrícula.', 'success').then((value) => {
                window.location = "verSolicitacoes.php";
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível concluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function solicitacaoExistente(){
            swal('Solicitação Já Cadastrada' ,'Já foi feita uma solicitação de matrícula para essa criança anteriormente referente ao período de matrícula atual!', 'error').then((value) => {
                window.location = "verSolicitacoes.php";
            });
        }

        function vagasEsgotadas(data, crianca, creche, periodo){
            swal({
                title: "Vagas Esgotadas!",
                text: "Deseja entrar na fila de espera?",
                icon: "warning",
                buttons: true,
                dangerMode: false,
                }).then((willFila) => {
                if(willFila){
                    adicionarNaFila(data, crianca, creche, periodo);
                }else{
                    window.location = "solicitarMatricula.php";
                }
            });
        }

        function adicionarNaFila(data_hora, tb_crianca_id, tb_creche_id, tb_periodo_cadastro_id){
            let parametros = "data_hora="+ data_hora +"&crianca="+ tb_crianca_id +"&creche="+ tb_creche_id +"&periodo="+ tb_periodo_cadastro_id;
            window.location = "adicionarNaFila.php?" + parametros;
        }
    </script>
</head>
<body>
<?php
    //menu de navegação
    include_once("telaDeFundoResponsavel.php");
?> 
    

<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //receber os dados que vieram do form via POST
    $idCrianca = $_POST["filho"];
    $idCreche = $_POST["crecheNI"];
    $idPeriodo = $_POST["idPer"];

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = date('Y-m-d H:i:s');
    $matriculado = 1; 
    $posicaoFilaEspera = 0;
    $isReserva = 1;


    //criar o comando sql do insert
    $sql = "INSERT INTO tb_cadastro (data_hora, matriculado, tb_crianca_id, tb_creche_id, tb_periodo_cadastro_id, posicaoFila, isReserva)
    VALUES ('$dataHoraAtual', '$matriculado', '$idCrianca', '$idCreche', '$idPeriodo', '$posicaoFilaEspera', '$isReserva')";

    //sql para verificar se já foi feito uma solicitação de matrícula para aquela criança
    $sqlVerificaCrianca = "SELECT * FROM tb_cadastro WHERE tb_crianca_id = $idCrianca AND tb_periodo_cadastro_id = $idPeriodo";
    $retornoVerificaCrianca = $conn->query($sqlVerificaCrianca);
    

    //sql para verificar se ainda há vagas disponíveis
    $sqlVagasDispon = "SELECT vagasDisponiveis FROM tb_periodo_cadastro_tb_creche WHERE tb_periodo_cadastro_id = $idPeriodo AND tb_creche_id = $idCreche";
    $retornoVagasDispon = $conn->query($sqlVagasDispon);
    $vagasDispon = $retornoVagasDispon->fetch_assoc();

    if($retornoVerificaCrianca->num_rows > 0){
        ?>
            <script>
                solicitacaoExistente();
            </script>

            <?php        
    }
    elseif($vagasDispon["vagasDisponiveis"] == 0){
        ?>
        <script>
            vagasEsgotadas("<?php echo $dataHoraAtual; ?>", "<?php echo $idCrianca; ?>", "<?php echo $idCreche; ?>", "<?php echo $idPeriodo; ?>");            
        </script>
        <?php
    }
    else{
        
        //executar o comando sql de inserção
        if($conn->query($sql) === TRUE) {
            //atualiza as vagas disponíveis naquela creche
            $sqlUpdateVagas = "UPDATE tb_periodo_cadastro_tb_creche SET vagasDisponiveis = vagasDisponiveis-1 WHERE tb_periodo_cadastro_id = $idPeriodo AND tb_creche_id = $idCreche";
            if($conn->query($sqlUpdateVagas) === TRUE){

                //envia mensagem de notificação para o usuário que pode ser vista pelo aplicativo
                $sqlNomeCrianca = "SELECT nome FROM tb_crianca WHERE id = $idCrianca";
                $retornoNomeCrianca = $conn->query($sqlNomeCrianca);
                $rowCrianca = $retornoNomeCrianca->fetch_assoc();

                $sqlNomeCreche = "SELECT nome FROM tb_creche WHERE id = $idCreche";
                $retornoNomeCreche = $conn->query($sqlNomeCreche);
                $rowCreche = $retornoNomeCreche->fetch_assoc();

                $mensagem = "Uma vaga na creche " . $rowCreche["nome"] ." foi reservada seu filho(a) " . $rowCrianca["nome"] . "! Aguarde o contato que será feito com você para finalizar a matrícula.";
                $cpf_resp = $_SESSION['cpf'];
                $sqlNotificacao = "INSERT INTO tb_notificacao (mensagem, tb_responsavel_Cpf) VALUES ('$mensagem', '$cpf_resp')";
                $executaInsercao = $conn->query($sqlNotificacao);

                ?>
                <script>
                    registroSalvo();
                </script>
    
                <?php
            }else{
                ?>
                <script>
                    erroRegistroSalvo();
                </script>

                <?php
            }            
        }
        else{
            ?>
            <script>
                erroRegistroSalvo();
            </script>

            <?php
        }
    }    
?>
</body>
</html>