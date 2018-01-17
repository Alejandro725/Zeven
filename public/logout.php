<?php
    require("../lib/database.php");
    require("../lib/sentencias.php");
    //Se destruyen las variables del cliente
    session_start();
    Sentencias::Actualizar("clientes", array('token' => 0), array('id' => $_SESSION["id_cliente"]), 0, null);
    unset($_SESSION["id_cliente"], $_SESSION["nombre_cliente"], $_SESSION["email_cliente"], $_SESSION["foto_cliente"]);
    header("Location: index.php");
?>