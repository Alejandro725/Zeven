<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("contraladores/clave.php");

    session_start();

    Render::Header("Carrito");
    Render::menuCliente();
    cambiarClave::usuarioClave();
    Render::Body();
?>