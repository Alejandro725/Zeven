<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("contraladores/tipo_usuario.php");

    session_start();

    Render::Header("Tipo Usuarios");

    if(isset($_SESSION["id"]))
    {
        Render::Menu();
        Render::Permisos("usuarios");
        opcionesTiposUsuarios::renderTiposUsuarios();
    }

    else
    {
        Ventanas::Mensaje(2, "Inicia sesion antes", "index.php");
    }
    
    Render::Body();
?>