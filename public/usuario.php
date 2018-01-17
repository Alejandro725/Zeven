<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/usuario.php");

    session_start();

    Render::Header("Usuario");
    Render::menuCliente();
    opcionesClientes::renderCliente();
?>

<?php    
    Render::Body();
?>