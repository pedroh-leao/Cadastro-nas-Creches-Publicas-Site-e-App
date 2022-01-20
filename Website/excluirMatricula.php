<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Criança</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(qualTela, idCreche){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                if(qualTela == 0){
                    window.location = "verSolicitacoes.php";
                }else if(qualTela == 2){
                    window.location = "filaDeEspera.php?idCreche=" + idCreche;
                }else{
                    window.location = "solicitacoesParaConfirmar.php";
                }
            });
        }

        function naoExclusao(){
            swal('Erro' ,'Não foi possível excluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }
          
    </script>
</head>
<body>

<?php
    include_once("checkIsLogged.php");
    if($_SESSION['tipoUsuario'] == "funcionario"){
        //tela de fundo
        include_once("telaDeFundoFuncionario.php");
    }else{
        //tela de fundo
        include_once("telaDeFundoResponsavel.php");
    }
    
    
?> 
    
<?php 

    include_once("conexao.php");

    //isset verifica se foi setado algum valor para $_get["id"]
    if(isset($_GET["crianca"]) && isset($_GET["creche"]) && isset($_GET["periodo"]) && isset($_GET["matriculado"])){

        $idCrianca = $_GET["crianca"];
        $idCreche = $_GET["creche"];
        $idPeriodo = $_GET["periodo"];
        $matriculado = $_GET["matriculado"];
        $isReserva = $_GET["isReserva"];

        $sql = "DELETE FROM tb_cadastro WHERE tb_crianca_id = $idCrianca AND tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo";

        //caso era a solicitação de matrícula de uma criança que estava na fila de espera:
        if($matriculado == 0){
            //pega posição na fila de espera da pessoa que está sendo excluída
            $sqlPosicao = "SELECT posicaoFila FROM tb_cadastro WHERE tb_crianca_id = $idCrianca AND tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo";
            $retornoPosicao = $conn->query($sqlPosicao);
            $rowPosicao = $retornoPosicao->fetch_assoc();

            //diminuirá em -1 a posição das pessoas que estavam atrás da solicitação que está sendo excluída da fila de espera 
            $sqlAtualizaFila = "UPDATE tb_cadastro SET posicaoFila = posicaoFila-1 WHERE tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo AND posicaoFila > " . $rowPosicao["posicaoFila"];

            if($conn->query($sqlAtualizaFila) === TRUE){
                if($conn->query($sql) === TRUE){
                    $sqlCriancaCancelada = "SELECT nome, tb_responsavel_Cpf FROM tb_crianca WHERE id = $idCrianca";
                    $retornoCriancaCancelada = $conn->query($sqlCriancaCancelada);
                    $criancaCancelada = $retornoCriancaCancelada->fetch_assoc();

                    $mensagemExclusao = "A solicitação de matrícula para seu filho(a) " . $criancaCancelada["nome"] . " foi cancelada e foi removida da fila de espera!";
                    $cpf_resp = $criancaCancelada["tb_responsavel_Cpf"];
                    $sqlNotificacaoExclusao = "INSERT INTO tb_notificacao (mensagem, tb_responsavel_Cpf) VALUES ('$mensagemExclusao', '$cpf_resp')";
                    $executaInsercao = $conn->query($sqlNotificacaoExclusao);

                    if(isset($_GET["qualTela"])){ //significa que veio de filaDeEspera.php e é para redirecionar para lá
                        ?>
                        <script>                                
                            exclusao('2', "<?php echo $idCreche ?>"); //matrícula excluída
                        </script>
                        <?php
                    }
                    else{ //significa que veio de verSolicitacoes.php ou buscarSolicitacoes.php e é para redirecionar para lá
                        ?>
                        <script>                                
                            exclusao('0', "<?php echo $idCreche ?>"); //matrícula excluída
                        </script>
                    <?php
                    }
                }else{
                    ?>
                    <script>
                        naoExclusao();
                    </script>
        
                    <?php 
                }
            }else{
                ?>
                <script>
                    naoExclusao();
                </script>
    
                <?php
            }
        }
        else{ //caso era a solicitação de matrícula de uma criança que estava matriculada ou com vaga reservada:

            //sql para pegar primeira criança da fila de espera e reservar uma vaga para ela tirando-a da fila de espera
            $sqlFirstCrianca = "SELECT * FROM tb_cadastro WHERE tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo AND posicaoFila = 1";
            $retorno = $conn->query($sqlFirstCrianca);
            $rowRetorno = $retorno->fetch_assoc();

            $sqlAtualizaStatus = "UPDATE tb_cadastro SET posicaoFila = 0, matriculado = 1, isReserva = 1 WHERE tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo AND posicaoFila = 1";
            if($conn->query($sqlAtualizaStatus) === TRUE){

                $sqlNomeCrianca = "SELECT nome, tb_responsavel_Cpf FROM tb_crianca WHERE id = " . $rowRetorno["tb_crianca_id"];
                $retornoNomeCrianca = $conn->query($sqlNomeCrianca);
                $rowNomeCrianca = $retornoNomeCrianca->fetch_assoc();

                $sqlNomeCreche = "SELECT nome FROM tb_creche WHERE id = $idCreche";
                $retornoNomeCreche = $conn->query($sqlNomeCreche);
                $rowCreche = $retornoNomeCreche->fetch_assoc();

                $mensagem = "Uma vaga na creche " . $rowCreche["nome"] ." foi reservada seu filho(a) " . $rowNomeCrianca["nome"] . "! Aguarde o contato que será feito com você para finalizar a matrícula.";
                $cpfResp = $rowNomeCrianca["tb_responsavel_Cpf"];
                $sqlNotificacao = "INSERT INTO tb_notificacao (mensagem, tb_responsavel_Cpf) VALUES ('$mensagem', '$cpfResp')";
                if($conn->query($sqlNotificacao) === TRUE){

                    $sqlAtualizaFila = "UPDATE tb_cadastro SET posicaoFila = posicaoFila-1 WHERE tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo AND posicaoFila > 0";
                    if($conn->query($sqlAtualizaFila) === TRUE){

                        if($conn->query($sql) === TRUE){
                            $sqlCriancaCancelada = "SELECT nome, tb_responsavel_Cpf FROM tb_crianca WHERE id = $idCrianca";
                            $retornoCriancaCancelada = $conn->query($sqlCriancaCancelada);
                            $criancaCancelada = $retornoCriancaCancelada->fetch_assoc();

                            if($isReserva == 1){
                                $mensagemExclusao = "A reserva de vaga para seu filho(a) " . $criancaCancelada["nome"] . " foi cancelada!";
                            }else{
                                $mensagemExclusao = "A matrícula do seu filho(a) " . $criancaCancelada["nome"] . " foi cancelada!";
                            }
                            
                            $cpf_resp = $criancaCancelada["tb_responsavel_Cpf"];
                            $sqlNotificacaoExclusao = "INSERT INTO tb_notificacao (mensagem, tb_responsavel_Cpf) VALUES ('$mensagemExclusao', '$cpf_resp')";
                            $executaInsercao = $conn->query($sqlNotificacaoExclusao);

                            if(isset($_GET["qualTela"])){ //significa que veio de solicitacoesParaConfirmar.php e é para redirecionar para lá
                                ?>
                                <script>                                
                                    exclusao('1', "<?php echo $idCreche ?>"); //matrícula excluída
                                </script>
                                <?php
                            }
                            else{ //significa que veio de verSolicitacoes.php ou buscarSolicitacoes.php e é para redirecionar para lá
                                ?>
                                <script>                                
                                    exclusao('0', "<?php echo $idCreche ?>"); //matrícula excluída
                                </script>
                            <?php
                            }
                        }else{
                            ?>
                            <script>
                                naoExclusao();
                            </script>
                
                            <?php 
                        }
                    }else{
                        ?>
                        <script>
                            naoExclusao();
                        </script>
            
                        <?php
                    }
                }else{
                    ?>
                    <script>
                        naoExclusao();
                    </script>
        
                    <?php
                }
            }else{
                ?>
                <script>
                    naoExclusao();
                </script>
    
                <?php
            }
        }
    }
?>
</body>
</html><?php 