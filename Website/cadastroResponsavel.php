<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="estilo.css">

    <title>Cadastro do Responsável</title>

    <style>
        #menuInicial ul li:last-child a {
        background-color: rgb(24, 139, 233);
        border-radius: 2px;
        }

        #botaoCadastrarMenuInicial{
            float: right;
            background-color: 
            rgb(24, 139, 233); 
            margin-left: 4px;
        }
    </style>

    <script>
        function formatar(mascara, documento){
            var i = documento.value.length;
            var saida = mascara.substring(0,1);
            var texto = mascara.substring(i)
            
            if (texto.substring(0,1) != saida){
                documento.value += texto.substring(0,1);
            }
        
        }
    </script>

</head>
<body>
    <?php
            //menu de navegação
            include_once("navegacaoInicial.html");
        ?>
    
   <div> 
        <h1 id="titulo">Cadastro</h1>
        <p id="subtitulo"> Complete suas informações:</p><br>
    </div>

    <div class="divCadastro">   
        <br> 
        <form name="formResposnsavel" action="inserirResponsavel.php" method="POST">

            <fieldset>
                <div>
                    <label for="nomeCompleto"><strong>Nome Completo:</strong></label><br>
                    <input type="text" name="nomeCompleto" id="nomeCompleto" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="dataNascimento"><strong>Data de nascimento:</strong></label><br>
                    <input type="date" name="dataNascimento" id="dataNascimento" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="cpf"><strong>CPF:</strong></label><br>
                    <input type="text" name="cpf" id="cpf" maxlength="14" class="inputCadastro" OnKeyPress="formatar('###.###.###-##', this)"><br><br>
                </div>
                <div>
                    <label for="telefone"><strong>Telefone de contato:</strong></label><br>
                    <input type="text" name="telefone" id="telefone" maxlength="13" class="inputCadastro" OnKeyPress="formatar('## #####-####', this)"><br><br>
                </div>
                <div>
                    <label for="bairro"><strong>Bairro:</strong></label><br>
                    <select name="bairro" id="bairro" class="selectCadastro" >
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
                                <option value="<?php echo $rowBairro["id"]; ?>"><?php echo $rowBairro["nome"]; ?></option>
                                <?php
                            }
                            ?>
                    </select> <br><br>
                </div>
                <div>
                    <label for="endereco"><strong>Endereço:</strong></label><br>
                    <input type="text" name="endereco" id="endereco" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="email"><strong>E-mail:</strong></label><br>
                    <input type="email" name="email" id="email" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="senha"><strong>Senha:</strong></label><br>
                    <input type="password" name="senha" id="senha" class="inputCadastro"><br><br>
                </div>
                <div>
                    <label for="confirmarSenha"><strong>Confirmar senha:</strong></label><br>
                    <input type="password" name="confirmarSenha" id="confirmarSenha" class="inputCadastro"><br><br>
                </div>  
            </fieldset>

            <br>

            <div>
                <button id="botaoCadastro" type="submit" >Concluído</button>
            </div>

        </form>
        <br>
    </div>

    <br><br><br><br><br>


</body>
</html>