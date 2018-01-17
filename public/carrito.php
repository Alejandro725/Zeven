<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/carrito.php");

    session_start();

    Render::Header("Carrito");
    opcionesCarrito::Carrito();
    Render::Navbar(null);
    Render::Body();
?>