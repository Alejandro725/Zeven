<?php
    //clase con las opciones para los usuarios
    class opcionesUsuarios
    {
        //metodo para la tabla de permisos
        public static function permisosUsuarios()
        {
            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //se declaran las variables active
            $active1 = null;
            $active2 = null;

            //Se cambian las variables en caso de que cumpla la condicion
            if(!empty($_GET["id_usuario"]))
            {
                $active1 = "active";
                $active2 = null;
            }

            echo
            ("
                <div class='row'>
                    <div class='col s10 offset-s1'>
                        <ul class='tabs'>
            ");

            //Aqui renderiza la tabla de permisos
            //Renderiza solo cuando tiene permisos de lectura
            if($dato_tipo["usuarios"] > 0)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active2 blue-text text-darken-4' href='#lista'>Lista de usuarios</a></li>
                ");
            }

            //Renderiza cuando tiene permisos para crear y modificar
            if($dato_tipo["usuarios"] == 2 || $dato_tipo["usuarios"] == 3 || $dato_tipo["usuarios"] > 4)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active1 blue-text text-darken-4' href='#registro'>Agregar usuario</a></li>
                ");
            }

            echo
            ("
                        </ul>
                    </div>
                </div>
            ");
        }

        //metodo para renderizar las opciones
        public  static function formularioUsuarios()
        {
            //se declara una variable que contendra los usuarios dependiendo de la opcion de busqueda
            $datos_usuarios = null;

            //se declaran las variables para usarse cuando se modifique un registro.
            $nombres = null;
            $apellidos = null; 
            $celular = null;
            $fijo = null;
            $dui = null;
            $direccion = null;
            $email = null;
            $usuario = null;
            $tipo = null;
            $estado = null;

            //Variables para añadir clase active
            $active1;
            $active2;

            //Verifica si se ha seleccionado un usuario a modificar
            if(!empty($_GET["id_usuario"]))
            {
                //variables para añadir la clase active
                $active1 = null;
                $active2 = "active";

                //variable con los datos de un usuario
                $datos_usuario = Sentencias::Seleccionar("usuarios", "id", array($_GET["id_usuario"]), 0, null);

                $nombres = $datos_usuario["nombres"];
                $apellidos = $datos_usuario["apellidos"]; 
                $celular = $datos_usuario["celular"];
                $fijo = $datos_usuario["fijo"];
                $dui = $datos_usuario["dui"];
                $direccion = $datos_usuario["direccion"];
                $email = $datos_usuario["email"];
                $usuario = $datos_usuario["usuario"];
                $tipo = $datos_usuarios["id_tipo"];
                $estado = $datos_usuarios["estado"];
            }

            else
            {
                //variables para añadir la clase active
                $active1 = "active";
                $active2 = null;
            }

            //variable con todos los tipos de usuario
            $datos_tipos_usuarios = Sentencias::Seleccionar("tipos_usuarios", null, null, 0, null);

            //aqui se renderizan las opciones dependiendo de los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Se valida que el formulario ya tenga datos
            if(!empty($_POST))
            {   
                //Se llenan  las variables con los datos del formulario
                $nombres = $_POST["nombres"];
                $apellidos = $_POST["apellidos"]; 
                $celular = $_POST["celular"];
                $fijo = $_POST["fijo"];
                $dui = $_POST["dui"];
                $direccion = $_POST["direccion"];
                $email = $_POST["email"];
                $usuario = $_POST["usuario"];
                $tipo = $_POST["tipo"];
                $estado = $_POST["estado"];
                $clave1 = $_POST["clave1"];
                $clave2 = $_POST["clave2"];
                $img = $usuario.time().".jpg";
                $intentos = 0;
                $token = 0;

                //Se valida que ninguno este vacio
                if($nombres != "" && $apellidos != "" && $celular != "" && $fijo != "" && $dui != "" && $direccion != "" && $email != "" && $usuario != "")
                {
                    //Se valida el nombre del usuario
                    if(Validaciones::nombre($nombres) && Validaciones::longitud($nombres, 30))
                    {
                        //Se validan los apellidos
                        if(Validaciones::nombre($apellidos) && Validaciones::longitud($apellidos, 30))
                        {
                            //Se valida el numero celular
                            if(Validaciones::numero($celular))
                            {
                                //Se valida el numero fijo
                                if(Validaciones::numero($fijo))
                                {
                                    //Se valida el dui
                                    if(Validaciones::dui($dui))
                                    {
                                        //Se valida la direccion
                                        if(Validaciones::alfanumerico($direccion))
                                        {
                                            //Se valida la direccion de correo
                                            if(filter_var($email, FILTER_VALIDATE_EMAIL))
                                            {
                                                //Se valida el nombre de usuario
                                                if(Validaciones::alfanumerico($usuario) && Validaciones::longitud($usuario, 25))
                                                {
                                                    //Aqui se divide entre si es agregar o modificar
                                                    //Aqui se modifca
                                                    if(!empty($_GET["id_usuario"]))
                                                    {
                                                        //Se valida si es un numero
                                                        if(is_numeric($_GET["id_usuario"]))
                                                        {
                                                            $campos_valores = array
                                                            (
                                                                'nombres' => $nombres,
                                                                'apellidos' => $apellidos,
                                                                'celular' => $celular,
                                                                'fijo' => $fijo,
                                                                'dui' => $dui,
                                                                'direccion' => $direccion,
                                                                'email' => $email,
                                                                'usuario' => $usuario,
                                                                'id_tipo' => $tipo,
                                                                'estado' => 1,
                                                            );

                                                            $condiciones_parametros = array
                                                            (
                                                                'id' => $_GET["id_usuario"]
                                                            );

                                                            Sentencias::Actualizar("usuarios", $campos_valores, $condiciones_parametros, 1, "ver_usuarios.php");
                                                        }

                                                        else
                                                        {
                                                            Ventanas::Mensaje(2, "El parametro no es valido", null);
                                                        }
                                                    }

                                                    //Aqui inicia para agregar un nuevo usuario
                                                    else
                                                    {
                                                        //Comprueba que no haya espacios vacios antes de agregar
                                                        if($clave1 != "" && $clave2 != "" && !empty($_FILES["archivo"]))
                                                        {
                                                            //Valida la imagen
                                                            if(Validaciones::imagen($_FILES["archivo"]))
                                                            {
                                                                if(strlen($clave1) > 7)
                                                                {
                                                                    if($clave1 != 12345678)
                                                                    {
                                                                        if($clave1 != $usuario)
                                                                        {
                                                                            if($clave1 == $clave2)
                                                                            {
                                                                                $clave = password_hash($clave1, PASSWORD_DEFAULT);

                                                                                $campos_valores = array
                                                                                (
                                                                                    'nombres' => $nombres,
                                                                                    'apellidos' => $apellidos,
                                                                                    'celular' => $celular,
                                                                                    'fijo' => $fijo,
                                                                                    'dui' => $dui,
                                                                                    'direccion' => $direccion,
                                                                                    'email' => $email,
                                                                                    'usuario' => $usuario,
                                                                                    'clave' => $clave,
                                                                                    'foto' => $img,
                                                                                    'id_tipo' => $tipo,
                                                                                    'estado' => 2,
                                                                                    'intentos' => 0,
                                                                                    'token' => 0,
                                                                                );

                                                                                Sentencias::Insertar('usuarios', $campos_valores, 1, 'usuarios.php');

                                                                                move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/usuarios/$img");
                                                                            }

                                                                            else
                                                                            {
                                                                                Ventanas::Mensaje(2, "Las claves deben ser iguales", null);
                                                                            }
                                                                        }

                                                                        else
                                                                        {
                                                                            Ventanas::Mensaje(2, "La clave no debe ser igual al nombre de usuario", null);
                                                                        }
                                                                    }

                                                                    else
                                                                    {
                                                                        Ventanas::Mensaje(2, "La clave no es segura", null);
                                                                    }
                                                                }

                                                                else
                                                                {
                                                                    Ventanas::Mensaje(2, "La clave debe tener como minimo 8 caracteres", null);
                                                                }
                                                            }

                                                            else
                                                            {
                                                                Ventanas::Mensaje(2, "La imagen no es valida", null);
                                                            }
                                                        }

                                                        else
                                                        {
                                                            Ventanas::Mensaje(2, "No deje espacios vacios", null);
                                                        }
                                                    }
                                                }

                                                else
                                                {
                                                    Ventanas::Mensaje(2, "El nombre de usuario no es valido", null);   
                                                }
                                            }

                                            else
                                            {
                                                Ventanas::Mensaje(2, "El correo electronico no es valido", null); 
                                            }
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "La direccion no es valida", null);  
                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "El dui no es valido", null);
                                    }
                                } 

                                else
                                {
                                    Ventanas::Mensaje(2, "El fijo celular no es valido", null);
                                } 
                            } 

                            else
                            {
                                Ventanas::Mensaje(2, "El numero celular no es valido", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "Los apellidos no son validos", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "Los nombres no son validos", null);
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "No deje espacios vacios", null);
                }
            }

            //Aqui empieza el formulario de usuarios
            echo
            ("
                <form method='post' enctype='multipart/form-data' id='registro'>
                    <div class='row'>
                        <div class='container'>
                            <div class='col s12 l10 offset-l1 center-align'>
                                <h4>Formulario de usuarios</h4>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='nombres' value='$nombres' name='nombres' type='text' class='validate' data-length='30'>
                                <label for='nombres' class='blue-text text-darken-4'>Nombres</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='apellidos' value='$apellidos' name='apellidos' type='text' class='validate' data-length='30'>
                                <label for='apellidos' class='blue-text text-darken-4'>Apellidos</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='celular' value='$celular' name='celular' type='text' data-length='8'>
                                <label for='celular' class='blue-text text-darken-4'>Numero Celular</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='fijo' value='$fijo' name='fijo' type='text' data-length='8'>
                                <label for='fijo' class='blue-text text-darken-4'>Numero Fijo</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='direccion' value='$direccion' name='direccion' type='text' data-length='100'>
                                <label for='direccion' class='blue-text text-darken-4'>Direccion</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='dui'value='$dui' name='dui' type='text' data-length='9'>
                                <label for='dui' class='blue-text text-darken-4'>Dui</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='usuario' value='$usuario' name='usuario' type='text' class='validate' data-length='25'>
                                <label for='usuario' class='blue-text text-darken-4'>Usuario</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='email' value='$email' name='email' type='email' class='validate' data-length='50'>
                                <label for='email' class='blue-text text-darken-4'>Email</label>
                            </div>
            ");

            //Aqui verifica si ya se ha seleccionado un usuario para usar esos datos
            if(!empty($_GET["id_usuario"]))
            {
                echo
                ("
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='tipo'>
                ");

                //Aqui se colocara el combobox de los tipos
                foreach($datos_tipos_usuarios as $row_tipos_usuarios)
                {
                    if($tipo == $row_tipos_usuarios["id"])
                    {
                        echo
                        ("
                            <option value='$row_tipos_usuarios[id]' selected>".$row_tipos_usuarios['tipo']."</option>
                        "); 
                    }

                    else
                    {
                        echo
                        ("
                            <option value='$row_tipos_usuarios[id]'>".$row_tipos_usuarios['tipo']."</option>
                        "); 
                    }
                }

                echo
                ("
                        </select>
                    </div>
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='estado'>
                ");

                //Aqui se coloca el combobox de estado de usuario
                if($estado == 1)
                {
                    echo
                    ("
                        <option value='1' selected>Activar</option>
                        <option value='0'>Desactivar</option>
                    ");
                }

                else if($estado == 0)
                {
                    echo
                    ("
                        <option value='1'>Activar</option>
                        <option value='0' selected>Desactivar</option>
                    ");
                }

                echo
                ("
                        </select>
                    </div>
                ");

                //Aqui se valida si tiene permisos para modificar
                if($dato_tipo["usuarios"] == 3 || $dato_tipo["usuarios"] == 5 || $dato_tipo["usuarios"] > 6)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button class='waves-effect waves-light btn blue darken-4'>Modificar usuario</button>
                        </div>
                    ");
                }

                echo
                ("
                    <div class='col s4 offset-s1 l4'>
                        <a href='usuarios.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                    </div>
                ");
            }

            //Aqui uso los  valores por defecto para los combobox
            else
            {
                echo
                ("
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='tipo'>
                ");

                //Aqui se colocara el combobox de los tipos
                foreach($datos_tipos_usuarios as $row_tipos_usuarios)
                {
                    echo
                    ("
                        <option value='$row_tipos_usuarios[id]'>".$row_tipos_usuarios['tipo']."</option>
                    "); 
                }

                //Aqui se coloca el combobox de estado de usuario
                echo
                ("
                        </select>
                    </div>
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='estado'>
                            <option value='1' selected>Activar</option>
                            <option value='0'>Desactivar</option>
                        </select>
                    </div>
                    <div class='input-field col s10 offset-s1 l6'>
                        <input id='clave1' name='clave1' type='password' class='validate'>
                        <label for='clave1'' class='blue-text text-darken-4'>Nueva contraseña</label>
                    </div>
                    <div class='input-field col s10 offset-s1 l6'>
                        <input id='clave2' name='clave2' type='password' class='validate'>
                        <label for='clave2' class='blue-text text-darken-4'>Repite contraseña</label>
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
                ");

                //Se valida si tiene los permisos para agregar usuarios
                if($dato_tipo["usuarios"] == 2 || $dato_tipo["usuarios"] == 5 || $dato_tipo["usuarios"] == 6 || $dato_tipo["usuarios"] == 9)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button class='waves-effect waves-light btn blue darken-4'>Agregar usuario</button>
                        </div>
                    ");
                }
            }

            echo
            ("
                        </div>
                    </div>
                </form>
            ");
            //Aqui termina el formulario de usuarios
        }

        //Metodo para ver tabla de usuarios
        public static function tablaUsuarios()
        {
            //se declara una variable que contendra los usuarios dependiendo de la opcion de busqueda
            $datos_usuarios = null;

            //aqui se renderizan las opciones dependiendo de los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Variable con todos los datos de tipos usuarios 
            $datos_tipos = Sentencias::Seleccionar("tipos", null, null, null, null);

            //Aqui empieza la tabla donde se mostraran los usuarios
            echo
            ("
                <div class='col s12' id='lista'>
                    <div class='col s12 center-align'>
                        <h4>Lista de usuarios</h4>
                    </div>
            ");

            //Se valida si ya hay una opcion de busqueda seleccionada
            if(!empty($_GET["accion"]))
            {
                if($_GET["accion"] == 1 || $_GET["accion"] == 2 || $_GET["accion"] == 3 || $_GET["accion"] == 4 || $_GET["accion"] == 5 || $_GET["accion"] == 6 || $_GET["accion"] == 7 || $_GET["accion"] == 8)
                {
                    switch ($_GET["accion"])
                    {
                        case 1:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por nombre de usuario</h4>
                                </div>
                            ");
                            break;

                        case 2:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por nombre del usuario</h4>
                                </div>
                            ");
                            break;

                        case 3:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por los apellidos del usuario</h4>
                                </div>
                            ");
                            break;


                        case 4:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por numero celular del usuario</h4>
                                </div>
                            ");
                            break;

                        case 5:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por numero fijo del usuario</h4>
                                </div>
                            ");
                            break;

                        case 6:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por dui del usuario</h4>
                                </div>
                            ");
                            break;

                        case 7:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por la direccion del usuario</h4>
                                </div>
                            ");
                            break;

                        case 8:
                            echo
                            ("
                                <div class='center-align'>
                                    <h4>Buscando por el correo</h4>
                                </div>
                            ");
                            break;

                        default:
                            # code...
                            break;
                    }

                    echo
                    ("
                        <form method='post'>
                            <div class='input-field col s2'>
                                <i class='material-icons prefix'>search</i>
                                <input id='buscar' type='text' class='validate' name='buscar'>
                                <label for='buscar'>Ingrese el nombre de usuario a buscar</label>
                            </div>
                            <div class='col s4 offset-s2 l2'>
                                <button class='waves-effect waves-light btn blue darken-4'>Buscar</button>
                            </div>
                            <div class='col s4 offset-s2 l2'>
                                <a href='usuarios.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                            </div>
                        </form>
                    ");

                    if(!empty($_POST["buscar"]))
                    {
                        $busqueda = trim($_POST["buscar"]);

                        switch ($_GET["accion"]) 
                        {
                            case 1:
                                if(Validaciones::longitud($busqueda, 25) && Validaciones::nombre($busqueda))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "usuario", array("$busqueda%"), 1, 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }                             
                                break;

                            case 2:
                                if(Validaciones::longitud($busqueda, 30) && Validaciones::nombre($busqueda))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "nombres", array($busqueda), 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }       
                                break;
                            case 3:
                                if(Validaciones::longitud($busqueda, 30) && Validaciones::nombre($busqueda))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "apellidos", array($busqueda), 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }    
                                break;

                            case 4:
                                if(Validaciones::longitud($busqueda, 8) && Validaciones::solo_numero($busqueda))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "celular", array($busqueda), 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }    
                                break;

                            case 5:
                                if(Validaciones::longitud($busqueda, 8) && Validaciones::solo_numero($busqueda))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "fijo", array($busqueda), 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }    
                                break;

                            case 6:
                                if(Validaciones::longitud($busqueda, 9) && Validaciones::solo_numero($busqueda))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "dui", array($busqueda), 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }    
                                break;

                            case 8:
                                if(Validaciones::longitud($busqueda, 50))
                                {
                                    $datos_usuarios = Sentencias::Seleccionar("usuarios", "email", array($busqueda), 1);
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                                }    
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }

                if($_GET["accion"] == 9)
                {
                    echo
                    ("
                        <div class='center-align'>
                            <h4>Buscando por el tipo de usuario</h4>
                        </div>

                        <form method='post' class='col s10 offset-s1 m12 offset-m2'>
                            <!--Se crea la barra de busqueda-->
                            <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                                <select name='buscar'>
                    ");

                    foreach($datos_tipos as $row_tipos)
                    {
                        /*echo
                        ("
                            <option value='$row_tipos[id]'>".$row_tipos['tipo']."</option>
                        ");*/
                        ("
                            <option value='1'>Hola</option>
                        ");
                    }

                    echo
                    ("
                                </select>
                            </div>
                            <div class='col s4 offset-s2 l4'>
                                <button class='waves-effect waves-light btn blue darken-4'>Buscar</button>
                            </div>
                            <div class='col s4 offset-s2 l4'>
                                <a href='usuarios.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                            </div>
                        </form>
                    ");

                    if(!empty($_POST))
                    {
                        $datos_usuarios = Sentencias::Seleccionar("usuarios", "id_tipo", array($_POST["buscar"]), 1, null);
                    }
                }

                if($_GET["accion"] == 10)
                {
                    echo
                    ("
                        <div class='center-align'>
                            <h4>Buscando por el estado del usuario</h4>
                        </div>

                        <div class='col s4 offset-s3'>
                            <a href='usuarios.php?accion=10&estado=1' class='waves-effect waves-light btn blue darken-4'>Activo</a>
                        </div>

                        <div class='col s4'>
                            <a href='usuarios.php?accion=10&estado=2' class='waves-effect waves-light btn blue darken-4'>Inactivo</a>
                        </div>
                    ");

                    if(!empty($_GET["estado"]))
                    {
                        if($_GET["estado"] == 1)
                        {
                            $datos_usuarios = Sentencias::Seleccionar("usuarios", "estado", array(1), 1, null);
                        }

                        if($_GET["estado"] == 2)
                        {
                            $datos_usuarios = Sentencias::Seleccionar("usuarios", "estado", array(0), 1, null);
                        }
                    }
                }
            }

            else
            {
                echo
                ("
                    <div class='center-align'>
                        <h4>Selecciona una opcion de busqueda en la tabla</h4>
                    </div>
                ");

                //variable que tiene todos los usuarios de la base
                $datos_usuarios = Sentencias::Seleccionar("usuarios", null, null, null, null);
            }

            //Se renderiza el esquema de la tabla
            echo
            ("
                <table class='highlight centered col s12'>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=1'>Usuario</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=2'>Nombre</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=3'>Apellido</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=4'>Celular</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=5'>Numero Fijo</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=6'>Dui</a></th>
                            <th>Direccion</th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=8'>Correo</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=9'>Tipo de Usuario</a></th>
                            <th><a class='blue-text text-darken-4' href='usuarios.php?accion=10'>Estado de Usuario</a></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                <tbody>
            ");

            //Aqui se termina de renderizar la tabla con las opciones de busqueda
            if($datos_usuarios != null)
            {
                foreach($datos_usuarios as $row_usuarios)
                {
                    /*Variables de estado*/
                    $estado;

                    if($row_usuarios["estado"] = 1)
                    {
                        $estado = "Activado";
                    }

                    else
                    {
                        $estado = "Desactivado";
                    }

                    //variable con el tipo de usuario de cada usuario en la base
                    $dato_tipo_usuario = Sentencias::Seleccionar("tipos_usuarios", "id", array($row_usuarios["id_tipo"]), 0, null);

                    //se renderiza cada usuario
                    echo
                    ("              
                                
                        <tr>
                            <td><img class='circle' width='50' height='50' src='../img/usuarios/$row_usuarios[foto]'></td>
                            <td>".$row_usuarios['usuario']."</td>
                            <td>".$row_usuarios['nombres']."</td>
                            <td>".$row_usuarios['apellidos']."</td>
                            <td>".$row_usuarios['celular']."</td>
                            <td>".$row_usuarios['fijo']."</td>
                            <td>".$row_usuarios['dui']."</td>
                            <td>".$row_usuarios['direccion']."</td>
                            <td>".$row_usuarios['email']."</td>
                            <td>".$dato_tipo_usuario['tipo']."</td>
                            <td>".$estado."</td>
                    ");

                    echo "<td>";

                    if($dato_tipo["usuarios"] == 3 || $dato_tipo["usuarios"] == 5 || $dato_tipo["usuarios"] > 6)
                    {
                        echo
                        ("
                            <a href='usuarios.php?id_usuario=".$row_usuarios['id']."' class='blue-text text-darken-4'><i class='material-icons'>mode_edit</i></a>
                        ");
                    }

                    if($dato_tipo["usuarios"] == 4 || $dato_tipo["usuarios"] > 5)
                    {
                        if($row_usuarios["estado"] == 1)
                        {
                            echo
                            ("
                                <a href='usuarios.php?activar=".$row_usuarios['id']."' class='blue-text text-darken-4'><i class='material-icons'>add</i></a>
                            ");
                        }

                        if($row_usuarios["estado"] == 0)
                        {
                            echo
                            ("
                                <a href='usuarios.php?desactivar=".$row_usuarios['id']."' class='blue-text text-darken-4'><i class='material-icons'>less</i></a>
                            ");
                        }
                    }

                    echo "</td>";
                            
                    echo "</tr>";
                }      
            }    

            echo
            ("
                        </tbody>
                    </table>
                </div>
            ");

            //Aqui termina de renderizar la tabla
        }
    }
?>