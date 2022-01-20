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

        <link rel="stylesheet" href="estilo.css">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    </head>

    <body >
        <?php
            // essa página tem uma lista que mostra apenas solicitações a serem confirmadas pelo funcionário

            //menu de navegação
            include_once("navegacaoFuncionario.html");
        ?>

        <br><br>
        <h2  style="text-align: center">Solicitações para confirmar</h2>
        <br><br><br>

        <form action="solicitacoesParaConfirmar.php" method="POST" id="form-pesquisa">
            <?php
                if(isset($_POST["idCreche"])){
                    $idCreche = $_POST["idCreche"];
                }else{
                    $idCreche = "";
                }
                if(isset($_POST["pesquisaCrianca"])){
                    $pesquisaCrianca = $_POST["pesquisaCrianca"];
                }else{
                    $pesquisaCrianca = "";
                }
            ?>
            <label for="idCreche"><strong>Selecione a creche que deseja pesquisar:</strong></label><br>
            <select style="font-size:  18px;" name="idCreche" id="idCreche" >
                <option value="" disabled <?php echo ($idCreche == "") ? "selected" : "" ?>>-- selecione uma creche --</option>
                <?php
                    //criar o comando sql
                    $sqlPesquisaCreche = "SELECT id, nome FROM tb_creche ORDER BY nome";

                    //executar o comando sql
                    $crechesPesquisadas = $conn->query($sqlPesquisaCreche);

                    while ($rowCrechePesq = $crechesPesquisadas->fetch_assoc()) { 
                        ?>
                        <option value="<?php echo $rowCrechePesq["id"]; ?>" <?php echo ($idCreche == $rowCrechePesq["id"]) ? "selected" : ""?>>
                            <?php echo $rowCrechePesq["nome"]; ?>
                        </option>
                        <?php
                    }
                ?>
            </select>
            <button id="botaoPesquisa" type="submit" class="botaoPesquisa">Pesquisar</button><br><br>

            <input type="text" name="pesquisaCrianca" id="pesquisaCrianca" class="inputPesquisa" placeholder="Nome da criança" value="<?php echo $pesquisaCrianca; ?>">
            <button id="botaoPesquisa" type="submit" class="botaoPesquisa" >Pesquisar</button><br><br>
        </form>

        
        <?php
        include_once("verificaDataPeriodo.php"); //traz a variavel $retorno caso haja um período de matrícula ativo

        if($retorno != 0){
        
            if($pesquisaCrianca != "" && $idCreche != ""){
                $sqlPesquisaCrianca = "SELECT id, nome FROM tb_crianca where nome like '%$pesquisaCrianca%' order by nome";
                $retornoPesquisaCrianca = $conn->query($sqlPesquisaCrianca);

                $sql = "SELECT * FROM tb_cadastro where isReserva = 1 and tb_creche_id = $idCreche and tb_periodo_cadastro_id = $retorno and (";
                $num_rows = $retornoPesquisaCrianca->num_rows;
                $contagem = 1;
                while($rowCrianca = $retornoPesquisaCrianca->fetch_assoc()){
                    if($contagem != $num_rows){
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " or";
                    }else{
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " )";
                    }
                    $contagem = $contagem + 1;
                }
            }
            elseif($pesquisaCrianca == "" && $idCreche == ""){
                $sql = "SELECT * FROM tb_cadastro where isReserva = 1 and tb_periodo_cadastro_id = $retorno";
            }
            elseif($idCreche != ""){
                $sql = "SELECT * FROM tb_cadastro where isReserva = 1 and tb_creche_id = $idCreche and tb_periodo_cadastro_id = $retorno";
            }
            elseif($pesquisaCrianca != ""){
                $sqlPesquisaCrianca = "SELECT id, nome FROM tb_crianca where nome like '%$pesquisaCrianca%' order by nome";
                $retornoPesquisaCrianca = $conn->query($sqlPesquisaCrianca);

                $sql = "SELECT * FROM tb_cadastro where isReserva = 1 and tb_periodo_cadastro_id = $retorno and (";
                $num_rows = $retornoPesquisaCrianca->num_rows;
                $contagem = 1;
                while($rowCrianca = $retornoPesquisaCrianca->fetch_assoc()){
                    if($contagem != $num_rows){
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " or";
                    }else{
                        $sql = $sql . " tb_crianca_id = " . $rowCrianca["id"] . " )";
                    }
                    $contagem = $contagem + 1;
                }
            }
        
            //echo $sql;
            //executar o comando
            $dadosCadastro = $conn->query($sql);
            
            //se número de registro retornados for maior que 0
            if ($dadosCadastro->num_rows > 0) {
            ?>
                <table >
                    <tr>
                        
                        <th>Criança</th>
                        <th>Creche</th>
                        <th>Cpf do responsável</th>
                        <th>Status</th>
                        <th>Período</th>
                        <th>Data/hora do envio</th>
                        <th>Confirmar</th>                        
                        <th>Cancelar</th>
                        
                    </tr>
                    <?php
                    while ($exibir = $dadosCadastro->fetch_assoc()) {
                        ?>
                        <tr>
                            <?php
                                $sqlCrianca = "SELECT * FROM tb_crianca WHERE id = " . $exibir["tb_crianca_id"];
                                $retornoCrianca = $conn->query($sqlCrianca);
                                $crianca = $retornoCrianca->fetch_assoc();
                            ?>
                            <td><?php echo $crianca["nome"] ?></td>

                            <?php
                                $sqlCreche = "SELECT nome FROM tb_creche WHERE id = " . $exibir["tb_creche_id"];
                                $retornoCreche = $conn->query($sqlCreche);
                                $nomeCreche = $retornoCreche->fetch_assoc();
                            ?>
                            <td> <?php echo $nomeCreche["nome"] ?></td>

                            <td><?php echo $crianca["tb_responsavel_Cpf"] ?></td>

                            <td><?php echo "Vaga reservada"; ?></td>

                            <?php
                                $sqlPeriodo = "SELECT data_inicio, data_fim FROM tb_periodo_cadastro WHERE id = ". $exibir["tb_periodo_cadastro_id"];
                                $retornoPeriodo = $conn->query($sqlPeriodo);
                                $rowPeriodo = $retornoPeriodo->fetch_assoc();
                            ?>
                            <td><?php echo date('d/m/Y',strtotime($rowPeriodo["data_inicio"])) . " - " . date('d/m/Y',strtotime($rowPeriodo["data_fim"])) ?></td>

                            <td><?php echo date('d/m/Y H:i', strtotime($exibir["data_hora"])) ?></td>

                            <td><a class="botaoConfirmar" href="#" onclick="confirmarSolicitacao(
                                '<?php echo $exibir["tb_crianca_id"] ?>',
                                '<?php echo $exibir["tb_creche_id"] ?>',
                                '<?php echo $exibir["tb_periodo_cadastro_id"] ?>')">Confirmar</a>
                            </td>

                            <td> <a class="botaoExcluir" href="#" onclick="cancelarSolicitacaoFuncionario(
                                '1',
                                '<?php echo $exibir["matriculado"] ?>',
                                '<?php echo $exibir["tb_crianca_id"] ?>',
                                '<?php echo $exibir["tb_creche_id"] ?>',
                                '<?php echo $exibir["tb_periodo_cadastro_id"] ?>',
                                '<?php echo $exibir["isReserva"] ?>')" >Cancelar</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            <?php
            }
            else{
                echo "<h4>Nenhum registro encontrado!</h4>"; 
            }
        }else{
            ?>

            <br><br><br>
            <h2><strong>Sem período de matrícula ativo nesse momento!</strong></h2>

            <?php
        }
        ?>
        <br><br><br><br><br>
    </body>

    <script>
        function confirmarSolicitacao(idCrianca, idCreche, idPeriodo){
            swal({
                title: "Deseja realmente confirmar a solicitação?",
                text: "A solicitação de matrícula será confirmada e a criança será matriculada na creche!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                if(willDelete){
                    window.location = "confirmarMatricula.php?idCrianca="+ idCrianca +"&idCreche="+ idCreche +"&idPeriodo="+ idPeriodo;
                };
            });
        }

        function cancelarSolicitacaoFuncionario(qualTela, matriculado, idCrianca, idCreche, idPeriodo, isReserva){
            swal({
                title: "Deseja realmente cancelar a solicitação?",
                text: "A solicitação de matrícula será cancelada!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                if(willDelete){
                    window.location = "excluirMatricula.php?crianca="+ idCrianca +"&creche="+ idCreche +"&periodo="+ idPeriodo +"&matriculado="+ matriculado +"&isReserva="+ isReserva +"&qualTela="+ qualTela;
                };
            });
        }
    </script>

</html>