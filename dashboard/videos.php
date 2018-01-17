<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("contraladores/video.php");

    session_start();

    Render::Header("Videos");

    if(isset($_SESSION["id"]))
    {
        $permiso = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

        if($permiso["videos"] > 0)
        {
            Render::Menu();
            Render::Permisos("videos");
            opcionesVideos::permisosVideos();
            opcionesVideos::formularioVideos();  
            opcionesVideos::tablaVideos();
                 
        }      

        else
        {
            header("Location: menu.php");
        }  
    }

    else
    {
        Ventanas::Mensaje(2, "Inicia sesion antes", "index.php");
    }
    
    Render::Body();
?>