<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visualizar dados da creche</title>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
        function resgirtroAtualizado(){
            swal('Sucesso!' ,'Registro atualizado com sucesso!', 'success').then((value) => {
                //voltará para a tabela de creches na qual o funcionário tem acesso
                window.location = "selecionarCreche.php";
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

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");

    include_once("conexao.php");

    if(isset($_POST["nomeCreche"])){
        $id = $_GET["id"];
        $nomeCreche = $_POST["nomeCreche"];
        $idBairro = $_POST["bairro"];

        $sqlUpdate = "UPDATE tb_creche
                    SET nome = '$nomeCreche', 
                    tb_bairro_id = $idBairro
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
            //menu de navegação
            include_once("navegacaoFuncionario.html");
        ?>

        <div> 
            <h1 id="titulo">Dados das creches</h1>
            <p id="subtitulo"> Veja as informações:</p><br>
        </div>
       

        <?php
        if(isset($_GET["id"])){
            $idCreche = $_GET["id"];
            $sqlcreche = "SELECT * from tb_creche where id = $idCreche";
            $consulta = $conn->query($sqlcreche);
            $creche = $consulta->fetch_assoc(); 
        }
        ?>
        <div class="divRUD">   
            <br>
            <form name="formCreche" action="RUDcreche.php?id=<?php echo $_GET['id']?>" method="post">
                <fieldset>
                    <div>                
                        <label for="nomeCreche"><strong>Nome:</strong></label><br>
                        <input type="text" name="nomeCreche" id="nomeCreche" class="inputEditar" value="<?php echo $creche["nome"] ?>"><br><br>
                    </div> 
                    <div>
                        <label for="bairro"><strong>Bairro:</strong></label><br>
                        <select name="bairro" id="bairro" class="selectEditar" >
                        <?php
                            //incluir o bd
                            include_once('conexao.php');

                            //buscar dados do dropdown no BD(tb_bairro)
                            //criar o comando sql
                            $sqlBairro = "SELECT id, nome FROM tb_bairro ORDER BY nome";

                            //executar o comando sql
                            $bairro = $conn->query($sqlBairro);

                            while ($rowBairro = $bairro->fetch_assoc()) { 
                                ?>
                                <option value="<?php echo $rowBairro["id"]; ?>" <?php echo ($rowBairro["id"] == $creche["tb_bairro_id"]) ? "selected" : ""?> ><?php echo $rowBairro["nome"]; ?></option>
                                <?php
                            }
                            ?>
                        </select><br><br>
                    </div>
                </fieldset>

                <br>

                <div>
                    <button id="botaoAlterarDados" type="submit" >Alterar dados</button>
                    <button id="botaoExcluirDados" type="reset" onclick= "confirmarExclusao(
                        '<?php echo $creche["id"] ?>',
                        '<?php echo $creche["nome"] ?>')">Excluir dados</button><br><br>

                </div>

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
                    window.location = "excluirCreche.php?id=" + id;
                };
            });

        }
    </script>
</html>