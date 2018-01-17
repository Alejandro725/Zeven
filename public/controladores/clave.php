<?php
    //clase en donde estara el metodo para cambiar contraseña
    class cambiarClave
    {
        //metodo para renderizar el formulario y las opciones
        public static function clienteClave()
        {
            //se valida que haya iniciado sesion
            if(!empty($_SESSION["id_cliente"]))
            {
                //Variable con todos los datos del usuario
                $datos_cliente = Sentencias::Seleccionar("clientes", "id", array($_SESSION["id_cliente"]), 0, null);

                //Se valida que solo pueda acceder si tiene estado 2
                if($datos_cliente["estado"] == 2)
                {
                    //Se valida que se haya presionado el boton del formulario
                    if(isset($_POST["formulario"]))
                    {
                        //se validan que los espacion no esten vacios
                        if($_POST["clave1"] != "" && $_POST["clave2"] != "")
                        {
                            //Se validan que tengan como minimo ocho caracteres
                            if(strlen($_POST["clave1"]) >= 8)
                            {
                                //Se valida que no sean tan faciles
                                if($_POST["clave1"] != 12345678)
                                {
                                    //Se valida que sea diferente a la contraseña anterior
                                    if(password_verify($_POST["clave1"], $datos_cliente["clave"]))
                                    {
                                        Ventanas::Mensaje(2, "La nueva clave debe ser diferente a la anterior", null);
                                    }
                                    
                                    else
                                    {
                                        //Se valida que la contraseña sea diferente al nombre de usuario
                                        if($_POST["clave1"] != $datos_cliente["usuario"])
                                        {
                                            //Se valida que las claves sean iguales
                                            if($_POST["clave1"] == $_POST["clave2"])
                                            {
                                                //Se prepara la sentencia para actualizar contraseña y estado
                                                $campos_valores = array
                                                (
                                                    'clave' => $_POST["clave1"],
                                                    'estado' => 1,
                                                    'token' => 0,
                                                    'intentos' => 0
                                                );

                                                $condiciones_parametros = array('id' => $_SESSION["id_cliente"]);

                                                Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);
                                                Ventanas::Mensaje(1, "Perfecto actualizaste tus claves", "usuarios.php");
                                            }

                                            else
                                            {
                                                Ventanas::Mensaje(2, "Las contraseñas deben ser iguales", null);
                                            }
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "La clave debe ser diferente al usuario", null);
                                        }
                                    }
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "La clave es muy facil", null);
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "Las contraseñas son muy cortas", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "No dejes espacios vacios", null);
                        }
                    }
                    //Se renderiza el formulario de las claves
                    echo
                    ("
                        <form method='post'>
                            <div class='col s12 center-align'>
                                <h4>Te recomendamos cambiar tu contraseña</h4>
                            </div>
                            <div class='row'>
                                <div class='container'>
                                    <div class='input-field col s10 offset-s1 l6'>
                                        <input id='clave1' type='password' name='clave1' class='validate' autocomplete='off'>
                                        <label for='clave1' class='blue-text text-darken-4'>Nueva contraseña</label>
                                    </div>
                                    <div class='input-field col s10 offset-s1 l6'>
                                        <input id='clave2' type='password' name='clave2' class='validate' autocomplete='off'>
                                        <label for='clave2' class='blue-text text-darken-4'>Repite contraseña</label>
                                    </div>
                                    <div class='col s4 offset-s1 l4'>
                                        <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Registrarse</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    ");
                }

                else
                {
                    header("Location: usuarios.php");
                }
            }
            
            else
            {
                header("Location: login.php");
            }
        }
    }
?>