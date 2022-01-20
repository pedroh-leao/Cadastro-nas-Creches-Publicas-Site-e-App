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
                window.location = "RUDresponsavel.php?Cpf=<?php echo $_GET['Cpf'] ?>";
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

    include_once("conexao.php");

    if (isset($_POST["nomeCompleto"])) {
        $cpf = $_GET["Cpf"];
        $cpfNovo = $_POST["cpf"];
        $nome = $_POST["nomeCompleto"];
        $data_de_nascimento = $_POST["dataNascimento"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
        $idBairro = $_POST["bairro"];
        $endereco = $_POST["endereco"];

        //criptografando a senha
        //$senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

        //criar o comando sql do update
        $sqlUpdate = "UPDATE tb_responsavel
                    SET Nome = '$nome', 
                    data_de_nascimento = '$data_de_nascimento',
                    Cpf = '$cpfNovo',
                    e_mail = '$email', 
                    telefone = '$telefone',
                    endereco = '$endereco', 
                    tb_bairro_id = $idBairro
                    WHERE Cpf = '$cpf'";


        if ($conn->query($sqlUpdate) === TRUE) {
    ?>
            <script>
                resgirtroAtualizado();

                //configurar depois para se estiver em uma sessão de conta de um funcionario ir para tela da tabela de contas
                //mas, se estiver na sessão do próprio responsavel voltar para essa mesma tela
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
    if ($_SESSION['tipoUsuario'] == "responsavel") {

        //menu de navegação
        include_once("navegacaoResponsavel.html");
    ?>

        <div>
            <h1 id="titulo">Meus dados</h1><br>
        </div>
        <br>
    <?php
    } else {

        //menu de navegação
        include_once("navegacaoFuncionario.html");
    ?>

        <div>
            <h1 id="titulo">Dados do responsável</h1><br>
        </div>
        <br>
    <?php
    }
    ?>

    <?php
    if (isset($_GET["Cpf"])) {
        $cpfResp = $_GET["Cpf"];
        $sqlResp = "SELECT * from tb_responsavel where Cpf = '$cpfResp'";
        $consultaResp = $conn->query($sqlResp);
        $responsavel = $consultaResp->fetch_assoc();
    } else {
        $cpfResp = $_SESSION['cpf'];
        $sqlResp = "SELECT * from tb_responsavel where Cpf = '$cpfResp'";
        $consultaResp = $conn->query($sqlResp);
        $responsavel = $consultaResp->fetch_assoc();
    }
    ?>
    <div class="divRUD">
        <br>

        <form name="formResposnsavel" action="RUDresponsavel.php?Cpf=<?php echo $cpfResp ?>" method="POST">

            <fieldset>
                <div>
                    <label for="nomeCompleto"><strong>Nome Completo:</strong></label><br>
                    <input type="text" name="nomeCompleto" id="nomeCompleto" class="inputEditar" value="<?php echo $responsavel["Nome"] ?>"><br><br>
                </div>
                <div>
                    <label for="dataNascimento"><strong>Data de nascimento:</strong></label><br>
                    <input type="date" name="dataNascimento" id="dataNascimento" class="inputEditar" value="<?php echo $responsavel["data_de_nascimento"] ?>"><br><br>
                </div>
                <div>
                    <label for="cpf"><strong>CPF:</strong></label><br>
                    <input type="text" name="cpf" id="cpf" maxlength="14" class="inputEditar" OnKeyPress="formatar('###.###.###-##', this)" value="<?php echo $responsavel["Cpf"] ?>"><br><br>
                </div>
                <div>
                    <label for="telefone"><strong>Telefone de contato:</strong></label><br>
                    <input type="tel" name="telefone" id="telefone" class="inputEditar" maxlength="13" OnKeyPress="formatar('## #####-####', this)" value="<?php echo $responsavel["telefone"] ?>"><br><br>
                </div>
                <div>
                    <label for="bairro"><strong>Bairro:</strong></label><br>
                    <select name="bairro" id="bairro" class="selectEditar">
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
                            <option value="<?php echo $rowBairro["id"]; ?>" <?php echo ($rowBairro["id"] == $responsavel["tb_bairro_id"]) ? "selected" : "" ?>><?php echo $rowBairro["nome"]; ?></option>
                        <?php
                        }
                        ?>
                    </select><br><br>
                </div>
                <div>
                    <label for="endereco"><strong>Endereço:</strong></label><br>
                    <input type="text" name="endereco" id="endereco" class="inputEditar" value="<?php echo $responsavel["endereco"] ?>"><br><br>
                </div>
                <div>
                    <label for="email"><strong>E-mail:</strong></label><br>
                    <input type="email" name="email" id="email" class="inputEditar" value="<?php echo $responsavel["e_mail"] ?>"><br><br>
                </div>
                <div>
                    <label for="seha"><strong>Alterar senha:</strong></label><br>
                    <button id="botaoAlterarDados" type="button" onclick="alterarSenha()">Alterar senha</button><br><br>
                </div>
            </fieldset>

            <button id="botaoAlterarDados" type="submit">Alterar dados</button>
            <button id="botaoExcluirDados" type="reset" onclick="confirmarExclusao(
                        '<?php echo $responsavel["Cpf"] ?>')">Excluir dados</button><br><br>

        </form>
    </div>

    <br><br><br><br><br>
</body>
<script>
    function confirmarExclusao(Cpf) {
        swal({
            title: "Deseja realmente excluir o registro?",
            text: "Você irá deletar todos os seus dados!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location = "excluirResponsavel.php?Cpf=" + Cpf;
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

        window.location = "confirmarSenhaResponsavel.php";

    }
</script>

</html>