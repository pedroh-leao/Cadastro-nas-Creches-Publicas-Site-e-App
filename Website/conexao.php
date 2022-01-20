<?php
    //parametros de conexão com BD
    $servername = "200.18.128.50"; //nome ou endereço ip do servidor
    $username = "cadastrocreche"; //nome do usuário
    $password = "2021@Cadastrocreche"; //senha de acesso ao servidor do banco de dados
    $dbname = "cadastrocreche"; //nome do banco de dados

    //criar um objeto de conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    //checar conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
?>