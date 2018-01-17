<?php
    require("../lib/PHPMailerAutoload.php");
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/problemas.php");

    session_start();

    Render::Header("Problemas");
    Recuperar::Clave();

    Render::Navbar(null);
    //Render::Footer();
?>

    <form method='post' class="col s12 l6 offset-l3" id="problemas">       
        <div class="col s12 center-align">
            <h4>Si tienes problemas con tu cuenta, puedes generar una nueva contraseña.</h4>
            <h4>Solo ingresa tu correo</h4>
        </div>
        <div class='row'>
            <div class='container'>
                <div class="input-field col s10 offset-s2 l8 offset-l2">
                    <i class='material-icons prefix blue-text text-darken-4'>email</i>
                    <input id="email" type="email" name='email' class="validate" autocomplete='off'>
                    <label for="email" class="blue-text text-darken-4">Email</label>
                </div>
                <div class="col s4 offset-s1 l4 offset-l2">
                    <button name='formulario' class="waves-effect waves-light btn blue darken-4">Generar nueva clave</button>
                </div>
                <div class="col s4 offset-s1 l2">
                    <a href="login.php" class="blue-text text-darken-4">Login</a>
                </div>
                <div class="col s4 offset-s1 l2">
                    <a href="registro.php" class="waves-effect waves-light blue-text text-darken-4">Crear una cuenta</a>
                </div>   
            </div>
        </div>
    </form>

<?php    
    Render::Body();
?>