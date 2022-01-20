<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar dados</title>

    <link rel="stylesheet" href="estilo.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function resgirtroAtualizado() {
            swal('Sucesso!', 'Registro atualizado com sucesso!', 'success').then((value) => {
                //voltará para a tabela de creches na qual o funcionário tem acesso
                window.location = "selecionarFuncionario.php";
            });
        }

        function erroResgirtroAtualizado() {
            swal('Erro!', 'Erro ao atualizar o registro!', 'error').then((value) => {
                window.history.back(); //simula o voltar do navegador
            });
        }
    </script>

</head>

<body>

    <?php
    //esse arquivo já tem o session_start()
    include_once("checkIsLogged.php");

    //checa se o tipo de usuario que esta tentando acessar a página tem a permissão
    include_once("isFuncionario.php");

    include_once("conexao.php");

    if (isset($_POST["nomeFuncionario"])) {
        $cpf = $_GET["CPF"];
        $cpfNovo = $_POST["cpf"];
        $nome = $_POST["nomeFuncionario"];
        $e_mail = $_POST["email"];
        //$senha = $_POST["senha"];
        $cargo_funcionario = $_POST["cargo"];



        //criar o comando sql do update
        $sqlUpdate = "UPDATE tb_funcionario_secretaria
                    SET nome = '$nome', 
                    CPF = '$cpfNovo',
                    e_mail = '$e_mail',
                    cargo_funcionario = '$cargo_funcionario'
                    WHERE CPF = '$cpf'";


        if ($conn->query($sqlUpdate) === TRUE) {
    ?>
            <script>
                resgirtroAtualizado();
                /*configurar depois para se estiver em uma sessão de conta de um funcionario ADM ir 
                para tela da tabela de contas dos outros funcionarios
                mas, se estiver na sessão do próprio funcionário voltar para essa mesma tela*/
            </script>
        <?php
        } else {
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
        <h1 id="titulo">Dados do Funcionário</h1>
        <p id="subtitulo"> Veja as informações:</p><br>
    </div>
    <br>

    <?php
    if (isset($_GET["CPF"])) {
        $cpf = $_GET["CPF"];
        $sqlfunc = "SELECT * from tb_funcionario_secretaria where CPF = '$cpf'";
        $consulta = $conn->query($sqlfunc);
        $funcionario = $consulta->fetch_assoc();
    } else {
        $cpf = $_SESSION['cpf'];
        $sqlfunc = "SELECT * from tb_funcionario_secretaria where CPF = '$cpf'";
        $consulta = $conn->query($sqlfunc);
        $funcionario = $consulta->fetch_assoc();
    }
    ?>

    <div class="divRUD">
        <br>
        <form name="formFuncionario" action="RUDfuncionario.php?CPF=<?php echo $cpf ?>" method="POST">

            <fieldset>
                <div>

                    <label for="nomeFuncionario"><strong>Nome:</strong></label><br>
                    <input type="text" name="nomeFuncionario" id="nomeFuncionario" class="inputEditar" value="<?php echo $funcionario["nome"] ?>"><br><br>

                </div>
                <div>
                    <label for="cpf"><strong>CPF:</strong></label><br>
                    <input type="text" name="cpf" id="cpf" maxlength="14" class="inputEditar" OnKeyPress="formatar('###.###.###-##', this)" value="<?php echo $funcionario["CPF"] ?>"><br><br>
                </div>
                <div>
                    <label for="cargo"><strong>Cargo:</strong></label><br>
                    <input type="text" name="cargo" id="cargo" class="inputEditar" value="<?php echo $funcionario["cargo_funcionario"] ?>"><br><br>
                </div>
                <div>
                    <label for="email"><strong>E-mail:</strong></label><br>
                    <input type="email" name="email" id="email" class="inputEditar" value="<?php echo $funcionario["e_mail"] ?>"><br><br>
                </div>
                <div>
                    <label for="seha"><strong>Alterar senha:</strong></label><br>
                    <button id="botaoAlterarDados" type="button" onclick="alterarSenha()">Alterar senha</button><br>
                </div>

            </fieldset>

            <br>

            <div>
                <button id="botaoAlterarDados" type="submit">Alterar dados</button>
                <button id="botaoExcluirDados" type="reset" onclick="confirmarExclusao(
                        '<?php echo $funcionario['CPF'] ?>')"> Excluir dados</button><br><br>
            </div>
        </form>
    </div>
    <br><br><br><br><br>

</body>

<script>
    function confirmarExclusao(CPF) {
        swal({
            title: "Deseja realmente excluir o registro?",
            text: "Você irá deletar todos os seus dados!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "excluirFuncionario.php?CPF=" + CPF;
            };
        });
    }

    function formatar(mascara, documento) {
        var i = documento.value.length;
        var saida = mascara.substring(0, 1);
        var texto = mascara.substring(i)

        if (texto.substring(0, 1) != saida) {
            documento.value += texto.substring(0, 1);
        }

    }

    function alterarSenha() {

        window.location = "confirmarSenhaFuncionario.php";

    }
</script>

</html>