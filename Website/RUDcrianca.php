<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar dados da crinça</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    function resgirtroAtualizado(){
        swal('Sucesso!' ,'Registro atualizado com sucesso!', 'success').then((value) => {
            //voltará para a tabela de creches na qual o funcionário tem acesso
            window.location = "selecionarCrianca.php";
        });
    }

    function erroResgirtroAtualizado(){
        swal('Erro!' ,'Erro ao atualizar o registro!', 'error').then((value) => {
            window.history.back();//simula o voltar do navegador
        });
    }
    </script>

    <link rel="stylesheet" href="estilo.css">

</head>

<body>

<?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");
    
    include_once("conexao.php");

    if(isset($_POST["nomeCompleto"])){
        $id = $_GET["id"];
        $nome = $_POST["nomeCompleto"];
        $dataNascimento = $_POST["dataNascimento"];

        $sqlUpdate = "UPDATE tb_crianca
                    SET nome = '$nome', 
                    data_de_nascimento = '$dataNascimento'
                    WHERE id = $id";

        
        if($conn->query($sqlUpdate) === TRUE){
            ?>
            <script>
                resgirtroAtualizado();
            </script>
            <?php
        }
        else{
            ?>
            <script>
                erroResgirtroAtualizado();
            </script>
            <?php
        }
    }
?>

    <?php
        if($_SESSION['tipoUsuario'] == "funcionario"){

            //menu de navegação
            include_once("navegacaoFuncionario.html");

        }
        else{

            //menu de navegação
            include_once("navegacaoResponsavel.html");
            
        }
    ?>

    <div>
        <h1 id="titulo">Dados da Criança</h1><br>
    </div>
    <br>

    <?php
        if(isset($_GET["id"])){
            $idcrianca = $_GET["id"];
            $sqlcrianca = "SELECT * from tb_crianca where id = $idcrianca";
            $consulta = $conn->query($sqlcrianca);
            $crianca = $consulta->fetch_assoc(); 
        }
        ?>
    <div class="divRUD">   
        <br>
        <form name="formCrianca" action="RUDcrianca.php?id=<?php echo $_GET['id']?>" method="POST">

            <fieldset>

                <div>
                    <label for="nomeCompleto"><strong>Nome Completo:</strong></label><br>
                    <input type="text" name="nomeCompleto" id="nomeCompleto" class="inputEditar" value="<?php echo $crianca["nome"] ?>"><br><br>
                </div>
                <div>
                    <label for="dataNascimento"><strong>Data de nascimento:</strong></label><br>
                    <input type="date" name="dataNascimento" id="dataNascimento" class="inputEditar" value="<?php echo $crianca["data_de_nascimento"] ?>"><br><br>
                </div>
                
            </fieldset>

            <button id="botaoAlterarDados" type="submit">Alterar dados</button>
            <button id="botaoExcluirDados" type="reset" onclick= "confirmarExclusao(
                        '<?php echo $crianca["id"] ?>',
                        '<?php echo $crianca["nome"] ?>')">Excluir dados</button><br><br>

        </form>
    </div>
    <br><br><br><br><br>
</body>

<script>
        function confirmarExclusao(id, nome){

            swal({
                title: "Deseja realmente excluir o registro?",
                text: "Você irá deletar a creche!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                if(willDelete){
                        window.location = "excluirCrianca.php?id=" + id;
                };
            });

        }
</script>

</html>