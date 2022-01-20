<?php
    if($_SESSION['tipoUsuario'] != "funcionario"){
        ?>
        <script>
            window.history.back();//simula o voltar do navegador
        </script>
        <?php
    }
?>