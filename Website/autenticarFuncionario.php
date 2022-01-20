<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    function erroRegistroSalvo(){
        swal('Erro' ,'Erro na autenticação do funcionário!', 'error').then((value) => {
            window.location = "selecionarFuncionario.php";
        });
    }
</script>
<?php
    include_once("conexao.php");

    //verificar se os atributos recebidos via POST não estão vazios
    if(isset($_POST["autorizado"]) && isset($_POST["CPF"])){

        $autorizado = $_POST["autorizado"];
        $cpf = $_POST["CPF"];

        $sqlUpdate = "UPDATE tb_funcionario_secretaria
                    SET autorizado = $autorizado
                    WHERE CPF = '$cpf'";
        
        if($conn->query($sqlUpdate) === TRUE){
            ?>
            <script>
                //voltará para a tabela de funcionários
                //window.location = "selecionarFuncionario.php";
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



    }else{
        echo "Erro nas variáveis recebidas!";
    }
?>