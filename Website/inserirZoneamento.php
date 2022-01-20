<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function registroSalvo(){
            swal('Sucesso' ,'Registro salvo com sucesso!', 'success').then((value) => {
                window.location = "cadastroZoneamento.php";
            });
        }

        function erroRegistroSalvo(){
            swal('Erro' ,'Não foi possível concluir o registro!!', 'error').then((value) => {
                window.history.back();
            });
        }

        function zoneamentoJaCadastrado(){
            swal('Erro' ,'Não foi possível concluir o registro pois essa creche já está cadastrada nesse bairro!!', 'error').then((value) => {
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

    //receber os dados que veio do form via POST
    if(isset($_POST["bairro"])){
        $creche = $_POST["creche"];

        if(!empty($_POST["bairro"])){            

            foreach($_POST["bairro"] as $bairro){

                //criando sql para verificar se ja foi cadastrado o bairro na creche
                $verificaBairro = "SELECT * FROM tb_zona WHERE tb_creche_id = '$creche' AND tb_bairro_id = '$bairro'";
                $consultaVerificacao = $conn->query($verificaBairro);

                //variável para ver se caso um unico bairro foi selecionado, e esse unico bairro ja foi cadastrado
                $jaCadastrado = 0;

                if($consultaVerificacao->num_rows == 0){
                    //criar o comando sql do insert
                    $sql = "INSERT INTO tb_zona (tb_creche_id, tb_bairro_id) VALUES ('$creche', '$bairro')";
                    //executar o comando sql do insert
                    $insercao = $conn->query($sql);

                    //caso o bairro seleciondao não foi cadastrado ainda ele recebe um incremento para não ter uma mensagem de erro
                    $jaCadastrado = $jaCadastrado+1;
                }
                
                //verificando se foi seleciodado um único bairro e se esse único bairro ja foi selecionado
                if($jaCadastrado == 0){
                    ?>
                    <script>
                        zoneamentoJaCadastrado();
                    </script>
                    
                    <?php
                    
                }
                
            }
    
        }  
        
        if($jaCadastrado != 0){

            if ($insercao === TRUE) {
                ?>
                <script>
                    registroSalvo();
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

        }

    }
     
?>
</body>
</html>