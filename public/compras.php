<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/compras.php");

    session_start();

    Render::Header("Compras");
    Render::menuCliente();
?>

<?php
    Render::Body();
?>