<?php
    if($_SESSION['tipoUsuario'] != "responsavel"){
        ?>
        <script>
            window.history.back();//simula o voltar do navegador
        </script>
        <?php
    }
?>