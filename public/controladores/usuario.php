<?php
    //clase para las opciones de los clientes
    class opcionesClientes
    {
        //metodo para renderzar el formulario del cliente
        public static function renderCliente()
        {
            //Se valida que haya iniciado sesion
            if(!empty($_SESSION["id_cliente"]))
            {
                //variable que contiene todos los datos del cliente
                $datos_cliente = Sentencias::Seleccionar("clientes", "id", array($_SESSION["id_cliente"]), 0, null);

                //Se declaran las variables que se usaran en el formulario
                $nombres = null;
                $apellidos = null;
                $usuario = null;
                $email = null;
                $telefono = null;
                $direccion = null;

                if(isset($_POST["formulario"]))
                {
                    //Se llenan las variables con los valores del formulario
                    $nombres = $_POST["nombres"];
                    $apellidos = $_POST["apellidos"];
                    $usuario = $_POST["usuario"];
                    $email = $_POST["email"];
                    $telefono = $_POST["telefono"];
                    $direccion = $_POST["direccion"];
                    $clave1 = $_POST["clave1"];
                    $clave2 = $_POST["clave2"];
                    $clave3 = $_POST["clave3"];

                    //Se valida que no hayan espacios en blanco
                    if($nombres != "" && $apellidos != "" && $usuario != "" && $email != "" && $telefono != "" && $direccion != "")
                    {
                        //Se valida el nombre
                        if(Validaciones::nombre($nombres) && Validaciones::longitud($nombres, 30))
                        {
                            //Se validan los apellidos
                            if(Validaciones::nombre($apellidos) && Validaciones::longitud($apellidos, 30))
                            {
                                //Se valida el nombre de usuario
                                if(Validaciones::alfanumerico($usuario) && Validaciones::longitud($usuario, 25))
                                {
                                    //Se valida el email
                                    if(filter_var($email, FILTER_VALIDATE_EMAIL) && Validaciones::longitud($email, 50))
                                    {
                                        //Se valida el numero
                                        if(Validaciones::numero($telefono))
                                        {
                                            //Se valida la direccion
                                            if(Validaciones::longitud($direccion, 100))
                                            {
                                                //Se divira entre si actualizar con o sin contraseña
                                                //Aqui se actualizaran con contraseñas contraseñas
                                                if($clave1 != "" || $clave2 != "" || $clave3 != "")
                                                {
                                                    //Se validan que los tres campos de contraseña no esten vacios
                                                    if($clave1 != "" && $clave2 != "" && $clave3 != "")
                                                    {
                                                        //Se valida que la clave antigua sea igual a la de la base de datos
                                                        if(password_verify($_POST["clave3"], $datos_cliente["clave"]))
                                                        {
                                                            //Se validan que las claves sean iguales
                                                            if($clave1 == $clave2)
                                                            {
                                                                //Se valida que la nueva clave sea diferente a la anterior
                                                                if($clave1 != $clave3)
                                                                {
                                                                    //Se empieza a preparar la sentencia para actualizar
                                                                    $clave = password_hash($clave1, PASSWORD_DEFAULT);

                                                                    $campos_valores = array
                                                                    (
                                                                        'nombres' => $nombres,
                                                                        'apellidos' => $apellidos,
                                                                        'usuario' => $usuario,
                                                                        'email' => $email,
                                                                        'clave' => $clave,
                                                                        'telefono' => $telefono,
                                                                        'direccion' => $direccion
                                                                    );

                                                                    $condiciones_parametros = array
                                                                    (
                                                                        'id' => $_SESSION["id_cliente"]
                                                                    );

                                                                    Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);
                                                                    Ventanas::Mensaje(1, "Perfecto, actualizaste tus datos", null);
                                                                }

                                                                else
                                                                {
                                                                    Ventanas::Mensaje(2, "La contraseña no puede ser igual a la anterior", null);
                                                                }                                                           
                                                            }

                                                            else
                                                            {
                                                                Ventanas::Mensaje(2, "Las nuevas contraseñas deben ser iguales", null);
                                                            }
                                                        }

                                                        else
                                                        {
                                                            Ventanas::Mensaje(2, "La contraseña antigua no coincide", null);
                                                        }
                                                    }

                                                    else
                                                    {
                                                        Ventanas::Mensaje(2, "Si vas  actualizar contraseñas, llena todos los campos", null);
                                                    }
                                                } 
                                                
                                                //Aqui actualiza sin contraseñas
                                                else
                                                {
                                                    //Se empieza a preparar la sentencia para actualizar
                                                    $campos_valores = array
                                                    (
                                                        'nombres' => $nombres,
                                                        'apellidos' => $apellidos,
                                                        'usuario' => $usuario,
                                                        'email' => $email,
                                                        'telefono' => $telefono,
                                                        'direccion' => $direccion
                                                    );

                                                    $condiciones_parametros = array
                                                    (
                                                        'id' => $_SESSION["id_cliente"]
                                                    );

                                                    Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);
                                                    Ventanas::Mensaje(1, "Perfecto, actualizaste tus datos", null);
                                                }
                                            }

                                            else
                                            {
                                                Ventanas::Mensaje(2, "La direccion no es valida", null);
                                            }
                                        }

                                        else
                                        {

                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "El email no es valido", null);
                                    }
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "El usuario no es valido", null);
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "Los apellidos no son validos", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El nombre no es valido", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "No deje espacios en blanco", null);
                    }
                }

                else
                {
                   $nombres = $datos_cliente["nombres"];
                    $apellidos = $datos_cliente["apellidos"];
                    $usuario = $datos_cliente["usuario"];
                    $email = $datos_cliente["email"]; 
                    $telefono = $datos_cliente["telefono"];
                    $direccion = $datos_cliente["direccion"];
                }

                //Se renderiza el formulario del cliente
                echo
                ("
                    <form method='post' id='datos'>
                        <div class='row'>
                            <div class='col s12'>
                                <div class='col s12 center-align'>
                                    <h4>Tus datos</h4>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input value='$nombres' id='nombres' name='nombres' type='text' class='validate'>
                                    <label for='disabled' class='blue-text text-darken-4'>Nombre</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input  value='$apellidos' id='apellidos' name='apellidos' type='text' class='validate'>
                                    <label for='disabled' class='blue-text text-darken-4'>Apellido</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input value='$usuario' id='usuario' name='usuario' type='text' class='validate'>
                                    <label for='usuario' class='blue-text text-darken-4'>Usuario</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input value='$email' id='email' name='email' type='email' class='validate'>
                                    <label for='email' class='blue-text text-darken-4'>Email</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input id='clave1' name='clave1' type='password' class='validate'>
                                    <label for='clave1' class='blue-text text-darken-4'>Nueva Contraseña</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input id='clave2' name='clave2' type='password' class='validate'>
                                    <label for='clave2' class='blue-text text-darken-4'>Repite tu contraseña</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l12'>
                                    <input id='clave3' name='clave3' type='password' class='validate'>
                                    <label for='clave3' class='blue-text text-darken-4'>Contraseña antigua</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input value='$telefono' id='telefono' name='telefono' type='text' data-length='8'>
                                    <label for='numero' class='blue-text text-darken-4'>Numero</label>
                                </div>
                                <div class='input-field col col s10 offset-s1 l6'>
                                    <input value='$direccion' id='direccion' name='direccion' type='text' data-length='50'>
                                    <label for='direccion' class='blue-text text-darken-4'>Direccion</label>
                                </div>
                                <div class='col s4 offset-s1 l4'>
                                    <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Modificar datos</button>
                                </div>
                            </div>                     
                        </div>
                    </form>
                ");
            }

            else
            {
                header("Location: login.php");
            }
        }
    }
?>