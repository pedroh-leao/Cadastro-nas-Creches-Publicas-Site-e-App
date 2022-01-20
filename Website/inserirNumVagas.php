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
            swal('Sucesso' ,'Registro salvo com sucesso!', 'success').then((value) => {
                window.location = "selecionarNumVagas.php";
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível concluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function vagasJacadastras(){
            swal('Erro' ,'Número de vagas já cadastrado para esta creche neste período!', 'error').then((value) => {
                window.history.back();
            });
        }
          
    </script>
</head>
<body>

<?php
    //menu de navegação
    include_once("telaDeFundoFuncionario.php");
?> 
    

<?php
    //incluir o arquivo de conexão com o BD
    include_once("conexao.php");

    //receber os dados que vieram do form via POST
    $numVagas = $_POST["numVagas"];
    $idCreche = $_POST["creche"];
    $idPeriodo = $_POST["periodoMatricula"];


    //criar o comando sql do insert
    $sql = "INSERT INTO tb_periodo_cadastro_tb_creche (tb_periodo_cadastro_id, tb_creche_id, numVagas, vagasDisponiveis)
    VALUES ('$idPeriodo', '$idCreche', '$numVagas', '$numVagas')"; //inicialmente as vagas disponiveis são iguais as iniciais

    //sql para conferir se já existe vagas cadastradas para aquela creche naquele período
    $sqlBusca = "SELECT * FROM tb_periodo_cadastro_tb_creche WHERE tb_periodo_cadastro_id = $idPeriodo AND tb_creche_id = $idCreche";
    $retornoBusca = $conn->query($sqlBusca);

    if($retornoBusca->num_rows > 0){
        ?>
        <script>
            vagasJacadastras();
            
        </script>

        <?php
    }else{
        //executar o comando sql de inserção
        if($conn->query($sql) === TRUE) {
            ?>
            <script>
                registroSalvo();
            </script>

            <?php
        }
        else{
            ?>
            <script>
                erroRegistroSalvo()
            </script>

            <?php
        }
    }    
?>
</body>
</html>