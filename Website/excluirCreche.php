<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Creche</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function exclusao(){
            swal('Sucesso' ,'Registro excluído com sucesso!', 'success').then((value) => {
                window.location = "selecionarCreche.php";
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
    //menu de navegação
    include_once("telaDeFundoFuncionario.php");
?> 

    <?php 

    include_once("conexao.php");

    //isset verifica se foi setado algum valor para $_get["id"]
    if(isset($_GET["id"])){

        $idCreche = $_GET["id"];

        $sql = "DELETE FROM tb_creche WHERE id = $idCreche";

        if($conn->query($sql) === TRUE ){
            ?>
                <script>
                    exclusao();
                </script>
            <?php

        }
        else{
            //Verificando se tem alguma vaga cadastrada na creche
            $sqlConsultVagas = "SELECT * FROM tb_periodo_cadastro_tb_creche WHERE tb_creche_id = $idCreche";

            $verificaVagas = $conn->query($sqlConsultVagas);

            //caso tenha alguma vaga cadastrada na creche, essa vaga também será excluida 
            if($verificaVagas -> num_rows > 0){
                $sqlDeleteVagas =  "DELETE FROM tb_periodo_cadastro_tb_creche WHERE tb_creche_id = $idCreche";
                if($conn->query($sqlDeleteVagas) === TRUE ){
                    $sql = "DELETE FROM tb_creche WHERE id = $idCreche";
                    if($conn->query($sql) === TRUE ){
                        ?>
                            <script>
                                exclusao();
                            </script>
                        <?php
                    }
                    
                }
                
            }
            //Verificando se tem algum zoneamento cadastrado na creche
            $sqlConsultZona = "SELECT * FROM tb_zona WHERE tb_creche_id = $idCreche";

            $verificaZona = $conn->query($sqlConsultZona);

            //caso tenha algum zoneamento cadastrado na creche, esse zoneamento também será excluida 
            if($verificaZona -> num_rows > 0){
                $sqlDeleteZona =  "DELETE FROM tb_zona WHERE tb_creche_id = $idCreche";
                if($conn->query($sqlDeleteZona) === TRUE ){
                    $sql = "DELETE FROM tb_creche WHERE id = $idCreche";
                    if($conn->query($sql) === TRUE ){
                        ?>
                            <script>
                                exclusao();
                            </script>
                        <?php
                    }
                    
                }
                
            }
            else{
                ?>
                    <script>
                        naoExclusao();
                    </script>

                <?php
            }

            

        }

    }
    else{
        echo "deu erro";
    }

    ?>
</body>
</html>    
