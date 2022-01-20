<?php
    session_start();

    if(!$_SESSION['logged']){
        session_destroy();
        header("location: index.php");
    }
?>