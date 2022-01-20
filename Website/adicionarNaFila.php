<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroSalvo(idCreche){
            swal('Sucesso' ,'Você foi adicionado à fila de espera.', 'success').then((value) => {
                window.location = "filaDeEspera.php?idCreche=" + idCreche;
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível concluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }
    </script>
</head>
<body>
<?php
    //menu de navegação
    include_once("telaDeFundoResponsavel.php");
?> 
    

<?php

    //cada creche possui uma fila de espera

    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //receber os dados que vieram do form via POST
    $idCrianca = $_GET["crianca"];
    $idCreche = $_GET["creche"];
    $idPeriodo = $_GET["periodo"];
    $dataHoraAtual = $_GET["data_hora"];
    $matriculado = 0; 
    $posicaoFilaEspera = 0;
    $isReserva = 0;

    //sql para definir a posicao na fila de espera
    $sqlPosicao = "SELECT posicaoFila FROM tb_cadastro WHERE tb_creche_id = $idCreche AND tb_periodo_cadastro_id = $idPeriodo";
    $retornoPosicao = $conn->query($sqlPosicao);
    while($rowPosicao = $retornoPosicao->fetch_assoc()){
        while($rowPosicao["posicaoFila"] >= $posicaoFilaEspera){
            $posicaoFilaEspera = $posicaoFilaEspera + 1;
        }
    }
    
    //criar o comando sql do insert
    $sql = "INSERT INTO tb_cadastro (data_hora, matriculado, tb_crianca_id, tb_creche_id, tb_periodo_cadastro_id, posicaoFila, isReserva)
    VALUES ('$dataHoraAtual', '$matriculado', '$idCrianca', '$idCreche', '$idPeriodo', '$posicaoFilaEspera', '$isReserva')";

    if($conn->query($sql) === TRUE){
        ?>
        <script>
            registroSalvo("<?php echo $idCreche ?>");
        </script>
        <?php
    }
    else{
        ?>
        <script>
            erroRegistroSalvo();
        </script>
        <?php
    }
?>
</body>
</html>