<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/producto.php");

    session_start();

    Render::Header("Productos");
    opcionesProductos::Producto();
    Render::Navbar(null);
    Render::Body();
?>