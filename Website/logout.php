<?php
    //inicia a session
    session_start();
    
    //destrói a session
    session_destroy();

    //redireciona para o menu inicial
    header("location: index.php");
?>