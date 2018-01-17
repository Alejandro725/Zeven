<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("contraladores/noticia.php");

    session_start();

    Render::Header("Noticias");

    if(isset($_SESSION["id"]))
    {
        $permiso = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

        if($permiso["noticias"] > 0)
        {
            Render::Menu();
            Render::Permisos("noticias");
            opcionesNoticias::permisosNoticias();
            opcionesNoticias::formularioNoticias();  
            opcionesNoticias::tablaNoticias();
                 
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