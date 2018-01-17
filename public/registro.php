<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/registro.php");

    session_start();

    Render::Header("Registro");

    Render::Navbar(null);
    
    opcionesRegistro::Registro();
?>

    <!--Aqui se colaca el formulario de registro-->
    <form method='post' enctype='multipart/form-data' class="col s12 l6 offset-l3" id="registro">
        <div class="col s12 center-align">
            <h4>Registro</h4>
        </div>
        <div class='row'>
            <div class='container'>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="nombre" type="text" name='nombre' class="validate" autocomplete='off'>
                    <label for="nombre" class="blue-text text-darken-4">Nombre</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="apellido" type="text" name='apellido' class="validate" autocomplete='off'>
                    <label for="apellido" class="blue-text text-darken-4">Apellido</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="usuario" type="text" name='usuario' class="validate" autocomplete='off'>
                    <label for="usuario" class="blue-text text-darken-4">Usuario</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="email" type="email" name='email' class="validate" autocomplete='off'>
                    <label for="email" class="blue-text text-darken-4">Email</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="clave1" type="password" name='clave1' class="validate" autocomplete='off'>
                    <label for="clave1" class="blue-text text-darken-4">Contraseña</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="clave2" type="password" name='clave2' class="validate" autocomplete='off'>
                    <label for="clave2" class="blue-text text-darken-4">Repite tu contraseña</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="numero" type="text" name='numero' data-length="8" autocomplete='off'>
                    <label for="numero" class="blue-text text-darken-4">Numero</label>
                </div>
                <div class="input-field col s10 offset-s1 l6">
                    <input id="direccion" type="text" name='direccion' data-length="50" autocomplete='off'>
                    <label for="direccion" class="blue-text text-darken-4">Direccion</label>
                </div>
                <div class='file-field input-field col s10 offset-s1 l12'>
                    <div class='btn blue darken-4'>
                        <span>Imagen</span>
                        <input type='file' name='archivo'>
                    </div>
                    <div class='file-path-wrapper'>
                        <input class='file-path validate' type='text' placeholder='Seleccione una imagen para el usuario'>
                    </div>
                </div>
                <!--<div class="g-recaptcha" data-sitekey="6LcRRioUAAAAABBCHy-wFOrpz5U3e2_NjNBJhwFF"></div>-->
                <div class="col s4 offset-s1 l4">
                    <button name='formulario' class="waves-effect waves-light btn blue darken-4">Registrarse</button>
                </div>
                <div class="col s4 offset-s1 l4">
                    <a href='login.php' class="waves-effect waves-light btn blue darken-4">Loguearse</a>
                </div>
            </div>
        </div>
    </form>
    <!--Aqui se termina el formulario de registro-->

<?php
    
    Render::Body();
?>