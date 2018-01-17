<?php
    //Clase para realizar el registro de un nuevo usuario
    class opcionesRegistro
    {
        //Metodo para el registro
        public static function Registro()
        {
            if(!empty($_SESSION["id_cliente"]))
            {
                header("Location: usuario.php");
            }

            //Verifica que se seleccione el boton de registro
            if(isset($_POST["formulario"]))
            {
                //Verifica que el metodo post no este vacio
                if(!empty($_POST))
                {
                    //Variables que tienen todos los datos del formulario
                    $nombre = trim($_POST["nombre"]);
                    $apellido = $_POST["apellido"];
                    $cliente = $_POST["usuario"];
                    $email = $_POST["email"];
                    $clave1 = $_POST["clave1"];
                    $clave2 = $_POST["clave2"];
                    $numero = $_POST["numero"];
                    $direccion = $_POST["direccion"];
                    $img = $cliente.time().".jpg";

                    //Valida que las variables no esten vacias
                    if($nombre != "" &&  $apellido !="" && $cliente != "" && $email != "" && $numero != "" && $direccion != "" && $clave1 && $clave2)
                    {
                        //Valida el nombre
                        if(Validaciones::nombre($nombre) && Validaciones::longitud($nombre, 30))
                        {
                            //Valida el apellido
                            if(Validaciones::nombre($apellido) && Validaciones::longitud($apellido, 30))
                            {
                                //Valida el nombre de cliente
                                if(Validaciones::nombre($cliente) && Validaciones::longitud($cliente, 25))
                                {
                                    //Valida el email
                                    if(Validaciones::email($email) && Validaciones::longitud($email, 50))
                                    {
                                        //Valida el numero de telefono
                                        if(Validaciones::numero($numero))
                                        {
                                            //Valida la direccion
                                            if(Validaciones::longitud($direccion, 100))
                                            {
                                                //Se valida si se ha suvido una imagen
                                                if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
                                                {
                                                    //Se valida la imagen
                                                    if(Validaciones::imagen($_FILES["archivo"]))
                                                    {
                                                        //Se valida que la clave sea diferente al nombre de usuario
                                                        if($cliente != $clave1)
                                                        {
                                                            //Se valida la longitud de la contraseña
                                                            if(strlen($clave1) > 7)
                                                            {
                                                                //Se valida que la clave sea segura
                                                                if($clave1 != 12345678)
                                                                {
                                                                    //Se valida que las contraseñas sean iguales
                                                                    if($clave1 == $clave2)
                                                                    {
                                                                        $clave = password_hash($clave1, PASSWORD_DEFAULT);

                                                                        $campos_valores = array
                                                                        (
                                                                            'nombres' => $nombre,
                                                                            'apellidos' => $apellido,
                                                                            'email' => $email,
                                                                            'usuario' => $cliente,
                                                                            'clave' => $clave,
                                                                            'foto' => $img,
                                                                            'telefono' => $numero,
                                                                            'direccion' => $direccion,
                                                                            'estado' => 1,
                                                                            'intentos' => 0,
                                                                            'token' => 0
                                                                        );

                                                                        Sentencias::Insertar("clientes", $campos_valores, 1, "login.php");
                                                                        move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/clientes/$img");
                                                                    }

                                                                    else
                                                                    {
                                                                        Ventanas::Mensaje(2, "Las claves deben ser iguales", null); 
                                                                    }
                                                                }

                                                                else
                                                                {
                                                                    Ventanas::Mensaje(2, "Tu clave no es segura", null);
                                                                }
                                                            }

                                                            else
                                                            {
                                                                Ventanas::Mensaje(2, "La clave debe tener como minimo ocho caracteres", null); 
                                                            }
                                                        }

                                                        else
                                                        {
                                                            Ventanas::Mensaje(2, "La clave no puede ser igual al usuario", null); 
                                                        }
                                                    }

                                                    else
                                                    {
                                                        Ventanas::Mensaje(2, "La imagen no es valida", null); 
                                                    }
                                                }

                                                else
                                                {
                                                    Ventanas::Mensaje(2, "Debe seleccionar una imagen", null); 
                                                }
                                            }

                                            else
                                            {
                                                Ventanas::Mensaje(2, "La direccion no es valida", null); 
                                            }
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "El numero de telefono no es valido", null); 
                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "El email de usuario no es valido", null); 
                                    }
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "El nombre de usuario no es valido", null);
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
                        Ventanas::Mensaje(2, "No deje espacios en blanco", null);
                    }
                }
            } 
            
        }
    }
?>