<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 

    session_start();

    Render::Header("");

    if(!empty($_SESSION["id"]))
    {
        $parametro = array($_SESSION["tipo"]);

        $datos_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", $parametro, 0);

        if($datos_tipo["usuarios"] >= 1)
        {
            header("Location: tipos_usuarios.php");
        }

        else if($datos_tipo["marcas"] >= 1)
        {
            header("Location: marcas.php");
        }

        else if($datos_tipo["productos"] >= 1)
        {
            header("Location: productos.php");
        }

        else if($datos_tipo["noticias"] >= 1)
        {
            header("Location: noticias.php");
        }

        else if($datos_tipo["clientes"] >= 1)
        {
            header("Location: clientes.php");
        }

        else if($datos_tipo["comentarios"] >= 1)
        {
            header("Location: comentarios.php");
        }

        else if($datos_tipo["videos"] >= 1)
        {
            header("Location: videos.php");
        }
        
        else if($datos_tipo["pedidos"] >= 1)
        {
            header("Location: pedidos.php");
        }
    }

    else
    {
        Ventanas::Mensaje(2, "Debes haber iniciado sesion", "index.php");
    }

    Render::Body();
?>