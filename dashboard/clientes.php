<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    //require("contraladores/cliente.php");

    session_start();

    Render::Header("Clientes");

    if(isset($_SESSION["id"]))
    {
        $permiso = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

        if($permiso["clientes"] > 0)
        {
            Render::Menu();
            Render::Permisos("clientes");
            /*opcionesMarcas::permisosMarcas();
            opcionesMarcas::tablaMarcas();
            opcionesMarcas::formularioMarcas();*/
            echo
            ("
                <a href='reportes/clientes_mes.php' class='blue-text text-darken-4'>Clientes registrados este mes</a>
            ");         
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