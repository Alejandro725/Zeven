<?php
    require("../lib/database.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php");
     
    session_start();

    $campos_valores = array
    (
        'token' => 0
    );

    $condiciones_parametros = array
    (
        'id' => $_SESSION["id"]
    );

    Sentencias::Actualizar("usuarios", $campos_valores, $condiciones_parametros, 0, null);

    unset($_SESSION["id"], $_SESSION["tipo"], $_SESSION["nombre"], $_SESSION["foto"]);

    header("Location: index.php");
?>