<?php
    //clase para renderizar el index o el registro
    class Opciones
    {
        public static function Registro()
        {
            Render::Header("Registro");

            if(!empty($_POST))
            {
                $nombre = $_POST["nombre"];
                $apellido = $_POST["apellido"];
                $numero = $_POST["numero"];
                $fijo = $_POST["fijo"];
                $direccion = $_POST["direccion"];
                $dui = $_POST["dui"];
                $usuario = $_POST["usuario"];
                $email = $_POST["email"];
                $clave1 = $_POST["clave1"];
                $clave2 = $_POST["clave2"];
                $img = $usuario.time().".jpg";

                if($nombre != "" && $apellido != "" && $numero != "" && $fijo != "" && $direccion != "" && $dui != "" && $usuario != "" && $email != "" && $clave1 != "" && $clave2 != "" && !empty($_FILES["archivo"]))
                {
                    //Se valida el nombre
                    if(Validaciones::nombre($nombre) && Validaciones::longitud($nombre, 30))
                    {
                        //Se valida el apellido 
                        if(Validaciones::nombre($apellido) && Validaciones::longitud($apellido, 30))
                        {
                            //Se valida el numero celular
                            if(Validaciones::numero($numero))
                            {
                                //Se valida el numero fijo
                                if(Validaciones::numero($fijo))
                                {
                                    //Se valida la direccion
                                    if(Validaciones::longitud($direccion, 100))
                                    {
                                        //Se valida el dui
                                        if(Validaciones::dui($dui))
                                        {
                                            //Se valida el usuario
                                            if(Validaciones::alfanumerico($usuario) && Validaciones::longitud($usuario, 25))
                                            {
                                                //Se valida el email
                                                if(Validaciones::email($email) && Validaciones::longitud($email, 50))
                                                {
                                                    //Se valida la imagen
                                                    if(Validaciones::imagen($_FILES["archivo"]))
                                                    {
                                                        //Se validad las contraseñas
                                                        if($clave1 == $clave2)
                                                        {
                                                            $clave = password_hash($clave1, PASSWORD_DEFAULT);

                                                            $datos = array
                                                            (
                                                                'nombres' => $nombre,
                                                                'apellidos' => $apellido,
                                                                'celular' => $numero,
                                                                'fijo' => $fijo,
                                                                'dui' => $dui,
                                                                'direccion' => $direccion,
                                                                'email' => $email,
                                                                'usuario' => $usuario,
                                                                'clave' => $clave,
                                                                'foto' => $img,
                                                                'id_tipo' => 1,
                                                                'estado' => 1,
                                                                'token' => 0,
                                                                'intentos' => 0
                                                            );

                                                            Sentencias::Insertar('usuarios', $datos, 'index');

                                                            move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/usuarios/$img");
                                                        }   

                                                        else
                                                        {
                                                            Ventanas::Mensaje(2, "Las contraseñas deben ser identicas", null); 
                                                        }
                                                    }

                                                    else
                                                    {
                                                        Ventanas::Mensaje(2, "La imagen no es valida", null);
                                                    }
                                                    
                                                }

                                                else
                                                {
                                                    Ventanas::Mensaje(2, "El correo no es valido", null); 
                                                }
                                            }

                                            else
                                            {
                                                Ventanas::Mensaje(2, "El usuario no es valido", null); 
                                            }
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "El dui no es valido", null); 
                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "La direccion no es valida", null);
                                    }
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "El numero fijo no es valido", null);
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "El numero celular no es valido", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El apellido no es valido", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "El nombre no es valido", null);
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "No puede dejar espacios vacios", null);
                }
            }

            //Se renderiza el formulario de registro
            echo
            ("
                <form enctype='multipart/form-data' method='post'>
                    <div class='row'>
                        <div class='col s10 offset-s1 l8 offset-l2'>
                            <div class='white'>
                                <div class='center-align'>
                                    <h5 class='blue-text'>Aun no hay usuarios registrados, se el primero</h5>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>account_circle</i>
                                    <input id='nombre' name='nombre' type='text' class='validate' data-length='30'>
                                    <label for='nombre' class='blue-text text-darken-4'>Nombre</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>account_circle</i>
                                    <input id='apellido' name='apellido' type='text' class='validate' data-length='30'>
                                    <label for='apellido' class='blue-text text-darken-4'>Apellido</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>stay_current_portrait</i>
                                    <input id='numero' name='numero' type='text' class='validate' data-length='8'>
                                    <label for='numero' class='blue-text text-darken-4'>Numero Celular</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>phone</i>
                                    <input id='fijo' name='fijo' type='text' class='validate' data-length='8'>
                                    <label for='fijo' class='blue-text text-darken-4'>Numero Fijo</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>room</i>
                                    <input id='direccion' name='direccion' type='text' class='validate' data-length='100'>
                                    <label for='direccion' class='blue-text text-darken-4'>Direccion</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>perm_identity</i>
                                    <input id='dui' name='dui' type='text' class='validate' data-length='9'>
                                    <label for='dui' class='blue-text text-darken-4'>Dui</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>person_pin</i>
                                    <input id='usuario' name='usuario' type='text' class='validate' data-length='25'>
                                    <label for='usuario' class='blue-text text-darken-4'>Usuario</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>mail</i>
                                    <input id='email' name='email' type='email' class='validate' data-length='50'>
                                    <label for='email' class='blue-text text-darken-4'>Email</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>label</i>
                                    <input id='clave1' name='clave1' type='password' class='validate'>
                                    <label for='clave1'' class='blue-text text-darken-4'>Contraseña</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l6'>
                                    <i class='material-icons prefix blue-text text-darken-4'>label</i>
                                    <input id='clave2' name='clave2' type='password' class='validate'>
                                    <label for='clave2' class='blue-text text-darken-4'>Repite tu contraseña</label>
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
                                <div class='col s4 offset-s1 l4'>
                                    <button class='waves-effect waves-light btn blue darken-4'>Registrarse</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            ");

            Render::Body();
        }

        public static function Login()
        {
            Render::Header("Login");

            session_start();

            if(isset($_SESSION["id"]))
            {
                header("Location: menu.php");
            }

            else
            {
                if(!empty($_POST))
                {
                    //Se valida que no hayan espacios vacios
                    if($_POST["usuario"] != "" && $_POST["clave"] != "")
                    {
                        //Se valida el usuario
                        if(Validaciones::alfanumerico($_POST["usuario"]) && Validaciones::longitud($_POST["usuario"], 25))
                        {
                            $parametro = array($_POST["usuario"]);

                            $datos_usuario = Sentencias::Seleccionar("usuarios", "usuario", $parametro, 0, null);

                            //Se valida que exista ese usuario
                            if($datos_usuario != null)
                            {
                                //Se valida la clave del usuario
                                $hash = $datos_usuario["clave"];
                                if(password_verify($_POST["clave"], $hash))
                                {
                                    //Se valida que el usuario este activo
                                    if($datos_usuario["estado"] == 1 || $datos_usuario["estado"] == 2)
                                    {
                                        //Se valida si ya hay un token
                                        if($datos_usuario["token"] == 0)
                                        {
                                            $token = uniqid($datos_usuario['id'], true);
                                            $campos_valores = array('intentos' => 0, 'token' => $token);
                                            $condiciones_parametros = array('usuario' => $_POST["usuario"]);

                                            Sentencias::Actualizar("usuarios", $campos_valores, $condiciones_parametros, 0, null);

                                            //session_start();
                                            $_SESSION["id"] =$datos_usuario["id"];
                                            $_SESSION["tipo"] = $datos_usuario["id_tipo"];
                                            $_SESSION["nombre"] = $datos_usuario["nombres"] . " " . $datos_usuario["apellidos"];
                                            $_SESSION["foto"] = $datos_usuario["foto"];

                                            if($datos_usuario["estado"] == 1)
                                            {
                                                Ventanas::Mensaje(1, "Bienvenido", "menu.php");
                                            }                   

                                            if($datos_usuario["estado"] == 2)
                                            {
                                                Ventanas::Mensaje(1, "Bienvenido", "clave.php");
                                            }
                                        }

                                        else if($datos_usuario["token"] != 0)
                                        {
                                            Ventanas::Mensaje(2, "Esta cuenta ya esta en uso, si usted es el dueño original, contacte con el administrador", null);
                                        }                           
                                    }

                                    else if($datos_usuario["estado"] != 1)
                                    {
                                        Ventanas::Mensaje(2, "Este usuario esta desactivado, hable con el administrador para resolverlo", null);  
                                    }                              
                                }

                                else
                                {
                                    if($datos_usuario["intentos"] < 4)
                                    {
                                        $intentos = $datos_usuario["intentos"];

                                        $intentos++;

                                        $campos_valores = array('intentos' => $intentos);
                                        $condiciones_parametros = array('usuario' => $_POST["usuario"]);

                                        Sentencias::Actualizar("usuarios", $campos_valores, $condiciones_parametros, 0, null);

                                        Ventanas::Mensaje(2, "Usuario o contraseña incorrectos", null);
                                    }

                                    if($datos_usuario["intentos"] == 4)
                                    {
                                        $campos_valores = array('estado' => 0);
                                        $condiciones_parametros = array('usuario' => $_POST["usuario"]);

                                        Sentencias::Actualizar("usuarios", $campos_valores, $condiciones_parametros, 0, null);

                                        Ventanas::Mensaje(2, "Se ha exedido el numero de intentos, hable con el administrador para solucionar el problema", null);
                                    }
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "Usuario o contraseña incorrectos", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El usuario no es valido", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "No deje espacios vacios", null);
                    }            
                }

                //Se renderiza el login.
                echo
                ("
                    <form method='post'>
                        <div class='row'>
                            <div class='col s10 offset-s1 l6 offset-l1'>
                                <div class='center-align'>
                                    <h5 class='blue-text'>Ingresa tus crendencialess</h5>
                                </div>
                                <div class='input-field col s10 offset-s1 l12'> 
                                    <i class='material-icons prefix blue-text text-darken-4'>person_pin</i>
                                    <input id='usuario' name='usuario' type='text' class='validate' data-length='25'>
                                    <label for='usuario' class='blue-text text-darken-4'>Usuario</label>
                                </div>
                                <div class='input-field col s10 offset-s1 l12'>
                                    <i class='material-icons prefix blue-text text-darken-4'>label</i>
                                    <input id='clave' name='clave' type='password' class='validate'>
                                    <label for='clave' class='blue-text text-darken-4'>Contraseña</label>
                                </div>
                                <div class='col s4 offset-s1 l4'>
                                    <button class='waves-effect waves-light btn blue darken-4'>Registrarse</button>
                                </div>
                            </div>
                        </div>
                    </form>
                ");
            }

            Render::Body();
        }

        public static function Comprobar()
        {
            $data_usuarios = Sentencias::Seleccionar("usuarios", 0, 0, 1, null);

            if($data_usuarios == null)
            {
                self::Registro();
            }

            else
            {
                self::Login();
            }
        }
    }
?>