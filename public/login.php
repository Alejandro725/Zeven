<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/login.php");

    session_start();

    Render::Header("Login");
    opcionesLogin::Login();

    Render::Navbar(null);
    //Render::Footer();
?>

    <!--Aqui se agrega el formulario de login-->
    <form method='post' class="col s12 l6 offset-l3" id="login">       
        <div class="col s12 center-align">
            <h4>Ingresar</h4>
        </div>
        <div class='row'>
            <div class='container'>
                <div class="input-field col s10 offset-s1 l12">
                    <i class='material-icons prefix blue-text text-darken-4'>person_pin</i>
                    <input id="usuario" type="text" name='usuario' class="validate" autocomplete='off'>
                    <label for="usuario" class="blue-text text-darken-4">Usuario</label>
                </div>
                <div class="input-field col s10 offset-s1 l12">
                    <i class='material-icons prefix blue-text text-darken-4'>label</i>
                    <input id="clave" type="password" name='clave' class="validate" autocomplete='off'>
                    <label for="clave" class="blue-text text-darken-4">Contraseña</label>
                </div>
                <div class="col s4 offset-s1 l4">
                    <button name='formulario' class="waves-effect waves-light btn blue darken-4">Ingresar</button>
                </div>
                <div class="col s4 offset-s1 l4">
                    <a href="registro.php" class="waves-effect waves-light btn blue darken-4">Crear una cuenta</a>
                </div>
                <div class="col s4 offset-s1 l4">
                    <a href="problemas.php" class="blue-text text-darken-4">¿Problemas con tu cuenta?</a>
                </div>
            </div>
        </div>
    </form>
    <!--Aqui se termina el formulario de login-->

<?php    
    Render::Body();
?>