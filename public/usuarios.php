<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("contraladores/video.php");

    session_start();

    Render::Header($_SESSION["nombre_cliente"]);
    
    Render::Body();
?>