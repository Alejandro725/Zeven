<?php
    //Clase para generar el login
    class opcionesLogin
    {
        //Metodo para validar el login
        public static function Login()
        {
            //Se valida si ya tiene una cuenta abierta
            if(!empty($_SESSION["id_cliente"]))
            {
                header("Location: usuario.php");
            }      

            //Se valida que se presione el boton para login
            if(isset($_POST["formulario"]))
            {
                //Se valida que el formulario no este vacio
                if(!empty($_POST))
                {
                    //Se valida que no se dejen espacios vacios
                    if($_POST["usuario"] != "" && $_POST["clave"] != "")
                    {
                        //Se valida el usuario
                        if(Validaciones::alfanumerico($_POST["usuario"]) && Validaciones::longitud($_POST["usuario"], 25))
                        {
                            //Variable que tiene los datos de un cliente
                            $dato_cliente = Sentencias::Seleccionar("clientes", "usuario", array($_POST["usuario"]), 0, null);

                            //Se valida que existan datos con ese tipo de usuario
                            if($dato_cliente != null)
                            {
                                //Se valida la clave del usuario
                                $hash = $dato_cliente["clave"];

                                //Se valida que la contraseña coincida con la base
                                if(password_verify($_POST["clave"], $hash))
                                {
                                    //Se valida que el usuario este activo
                                    if($dato_cliente["estado"] == 1 || $dato_cliente["estado"] == 2)
                                    {
                                        //Se valida que el token sea cero
                                        if($dato_cliente["token"] == 0)
                                        {
                                            $token = uniqid($dato_cliente['id'], true);
                                            $campos_valores = array('intentos' => 0, 'token' => $token);
                                            $condiciones_parametros = array('usuario' => $_POST["usuario"]);

                                            Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);

                                            //session_start();
                                            $_SESSION["id_cliente"] =$dato_cliente["id"];
                                            $_SESSION["nombre_cliente"] = $dato_cliente["nombres"] . " " . $dato_cliente["apellidos"];
                                            $_SESSION["email_cliente"] = $dato_cliente["email"];
                                            $_SESSION["foto_cliente"] = $dato_cliente["foto"];

                                            if($dato_cliente["estado"] == 1)
                                            {
                                                Ventanas::Mensaje(1, "Bienvenido", "usuario.php");
                                            }

                                            if($dato_cliente["estado"] == 2)
                                            {
                                                Ventanas::Mensaje(1, "Bienvenido", "clave.php");
                                            }
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "Ya tienes una sesion abierta", null);
                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "Usuario inactivo, ", null);
                                    }
                                }

                                else
                                {
                                    if($dato_cliente["intentos"] < 4)
                                    {
                                        $intentos = $dato_cliente["intentos"];

                                        $intentos++;

                                        $campos_valores = array('intentos' => $intentos);
                                        $condiciones_parametros = array('usuario' => $_POST["usuario"]);

                                        Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);

                                        Ventanas::Mensaje(2, "Usuario o contraseña incorrectos", null);
                                    }

                                    if($dato_cliente["intentos"] == 4)
                                    {
                                        $campos_valores = array('estado' => 0);
                                        $condiciones_parametros = array('usuario' => $_POST["usuario"]);

                                        Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);

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
                            Ventanas::Mensaje(2, "El nombre de usuario no es valido", null);
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