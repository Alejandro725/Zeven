<?php
    //Clase con las opciones de videos
    class opcionesVideos
    {
        //Metodo para renderizar los permisos
        public static function permisosVideos()
        {
            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //se declaran las variables active
            $active1 = null;
            $active2 = null;

            //Se cambian las variables en caso de que cumpla la condicion
            if(!empty($_GET["id_video"]))
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
            if($dato_tipo["videos"] > 0)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active2 blue-text text-darken-4' href='#lista'>Lista de videos</a></li>
                ");
            }

            //Renderiza cuando tiene permisos para crear y modificar
            if($dato_tipo["videos"] == 2 || $dato_tipo["videos"] == 3 || $dato_tipo["videos"] > 4)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active1 blue-text text-darken-4' href='#registro'>Agregar videos</a></li>
                ");
            }

            echo
            ("
                        </ul>
                    </div>
                </div>
            ");
        }

        //Metodo para renderizar el formulario de registro
        public static function formularioVideos()
        {
            //Variables que se usaran cuando se seleccione un video a editar
            $link;
            $descripcion;

            //Variable que contendra los datos de un video ya seleccionado
            $data_video;

            //Se valida que se haya seleccionado un video a editar
            if(!empty($_GET["id_video"]))
            {   
                //Aqui se valida si el parametro ingresado es valido
                if(is_numeric($_GET["id_video"]))
                {
                    $data_video = Sentencias::Seleccionar("videos", "id", array($_GET["id_video"]), 0, null);

                    $link = $data_video["link"];
                    $descripcion = $data_video["descripcion"];
                }

                else
                {
                    header("Location: videos.php");
                }
            }

            else
            {
                $link = null;
                $descripcion = null;
            }


            //aqui se renderizan las opciones dependiendo de los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Empieza a validar para ingresar//modificar
            if(isset($_POST["formulario"]))
            {
                //Valida que el formulario no este vacio
                if(!empty($_POST))
                {
                    //Variables que se usaran para ingresar/modificar
                    $link = trim($_POST["link"]);
                    $descripcion = $_POST["descripcion"];
                    $estado = $_POST["estado"];

                    //Se valida que no hayan campos vacios
                    if($link != "" && $descripcion != "")
                    {
                        //Se valida que el link cumpla con los requisitos
                        if(Validaciones::longitud($link, 50))
                        {
                            //Se valida que la descripcion cumpla con los requisitos
                            if(Validaciones::longitud($descripcion, 15) && Validaciones::alfanumerico($descripcion))
                            {
                                //Aqui se divide para modificar o ingresar video
                                //Aqui se modifica  un video
                                if(!empty($_GET["id_video"]))
                                {
                                    $campos_valores = array 
                                    (
                                        'link' => $link,
                                        'descripcion' => $descripcion,
                                        'id_usuario' => $_SESSION["id"],
                                        'estado' => $estado 
                                    );

                                    $condiciones_parametros = array 
                                    (
                                        'id' => $_GET["id_video"]
                                    );

                                    Sentencias::Actualizar("videos", $campos_valores, $condiciones_parametros, 1, "videos.php");
                                }

                                //Aqui se agregan videos
                                else
                                {
                                    $campos_valores = array
                                    (
                                        'link' => $link,
                                        'descripcion' => $descripcion,
                                        'id_usuario' => $_SESSION["id"],
                                        'estado' => $estado
                                    );

                                    Sentencias::Insertar("videos", $campos_valores, 1, "videos.php");
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "La descripcion no es valida", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El link no es valido", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "No deje campos vacios", null);
                    }
                }
            }

            //Se renderiza el formulario para ingresa/modifcar videos
            echo
            ("
                <form method='post' id='registro'>
                    <div class='row'>
                        <div class='container'>
                            <div class='col s12 l10 offset-l1 center-align'>
                                <h4>Ingresa un nuevo video</h4>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='link' value='$link' name='link' type='text' class='validate' data-length='50'>
                                <label for='link' class='blue-text text-darken-4'>Link del video</label>
                            </div>  
            ");

            //Se coloca el combobox de estado de video dependiendo si se ha seleccionado un video o no
            //Aqui se renderiza si se ha seleccionado un video
            if(!empty($_GET["id_video"]))
            {
                //Aqui renderiza cuando esta activo
                if($data_video["estado"] == 1)
                {
                    echo
                    ("
                        <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                            <select name='estado'>
                                <option value='1' selected>Activar</option>
                                <option value='0'>Desactivar</option>
                            </select>
                        </div>
                    ");
                }

                //Aqui se renderiza cuando esta inactivo
                if($data_video["estado"] == 0)
                {
                    echo
                    ("
                        <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                            <select name='estado'>
                                <option value='1'>Activar</option>
                                <option value='0' selected>Desactivar</option>
                            </select>
                        </div>
                    ");
                }
            }

            //Aqui se renderiza de forma predetermina
            else
            {
                echo
                ("
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='estado'>
                            <option value='1' selected>Activar</option>
                            <option value='0'>Desactivar</option>
                        </select>
                    </div>
                ");
            }

            //Renderiza el espacio de texto para la descripcion
            echo
            ("
                <div class='input-field col s10 offset-s1 l12'>
                    <input id='descripcion' value='$descripcion' name='descripcion' type='text' class='validate' data-length='30'>
                    <label for='descripcion' class='blue-text text-darken-4'>Descripcion del video</label>
                </div>  
            ");

            //Renderiza el boton dependiendo de la accion y de los permisos de usuario
            //Aqui renderiza el boton para actualizar
            if(!empty($_GET["id_video"]))
            {
                //Renderiza solo si tiene permisos para actualizar videos
                if($dato_tipo["videos"] == 3 || $dato_tipo["videos"] == 5 || $dato_tipo["videos"] > 6)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Modificar video</button>
                        </div>
                    ");
                }
            }

            //Aqui renderiza el boton para agregar
            else
            {
                //Renderiza solo si tiene los permisos para crear
                if($dato_tipo["videos"] == 2 || $dato_tipo["videos"] == 5 || $dato_tipo["videos"] == 6 || $dato_tipo["videos"] == 9)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Ingresar video</button>
                        </div>
                    ");
                }
            }

            echo
            ("
                            <div class='col s4 offset-s1 l4'>
                                <a href='videos.php' class='waves-effect waves-light btn blue darken-4'>Limpiar campos</a>
                            </div>
                        </div>
                    </div>
                </form>
            ");
        }

        //Metodo para renderizar la tabla de videos
        public static function tablaVideos()
        {
            $datos_videos = null;

            echo
            ("
                <div class='row' id='lista'>
                    <div class='col s12 ' id='ver'>
                        <div class='col s12 center-align'>
                            <h4>Lista de videos</h4>
                        </div>
            ");

            //Se verifica si se selecciona una opcion de busqueda
            if(!empty($_GET["accion"]))
            {
                //Se valida que sea un numero
                if(is_numeric($_GET["accion"]))
                {
                    //Aqui se buscara por nombre de usuario
                    /*if($_GET["accion"] == 1)
                    {
                        echo
                        ("
                            <div class='center-align'>
                                <h4>Buscando por nombre de usuario</h4>
                            </div>
                        ");

                        //Se renderiza el formulario de busqueda
                        echo
                        ("
                            <form method='post' class='col s10 offset-s1 m12'>

                                <!--Se crea la barra de busqueda-->
                                <div class='input-field col s8'>
                                    <i class='material-icons prefix'>search</i>
                                    <input id='buscar' type='text' class='validate' name='buscar'>
                                    <label for='buscar'>Ingrese el nombre de usuario a buscar</label>
                                </div>
                                <div class='col s4 offset-s2 l2'>
                                    <button name='busqueda' class='waves-effect waves-light btn blue darken-4'>Buscar</button>
                                </div>
                                <div class='col s4 offset-s2 l2'>
                                    <a href='videos.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                                </div>
                            </form>
                        ");

                        //Se empieza a validar los valores ingresados para la busqueda
                        if(isset($_POST["busqueda"]))
                        {
                            $busqueda = trim($_POST["buscar"]);

                            if(Validaciones::longitud($busqueda, 20) && Validaciones::nombre($busqueda))
                            {
                                $datos_marcas = Sentencias::Seleccionar("marcas", "marca", array("$busqueda%"), 1, 1);
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                            }
                        }
                    }*/

                    //Aqui se buscara por estado
                    if($_GET["accion"] == 2)
                    {
                        echo
                        ("
                            <div class='center-align'>
                                <h4>Buscando por el estado del usuario</h4>
                            </div>

                            <div class='col s2 offset-s3'>
                                <a href='videos.php?accion=2&estado=1' class='waves-effect waves-light btn blue darken-4'>Activo</a>
                            </div>

                            <div class='col s2'>
                                <a href='videos.php?accion=2&estado=2' class='waves-effect waves-light btn blue darken-4'>Inactivo</a>
                            </div>
                            <div class='col s2'>
                                <a href='videos.php' class='waves-effect waves-light btn blue darken-4'>Limpair</a>
                            </div>
                        ");

                        if(!empty($_GET["estado"]))
                        {
                            if($_GET["estado"] == 1)
                            {
                                $datos_videos = Sentencias::Seleccionar("videos", "estado", array(1), 1, 1);
                            }

                            if($_GET["estado"] == 2)
                            {
                                $datos_videos = Sentencias::Seleccionar("videos", "estado", array(0), 1, 1);
                            }
                        }
                    }

                    else
                    {
                        header("Location: videos.php");   
                    }
                }

                else
                {
                    header("Location: videos.php");
                }
            }

            //Aqui usa los valores por defecto
            else
            {
                echo
                ("
                    <div class='center-align'>
                        <h4>Selecciona una opcion de busqueda en la tabla</h4>
                    </div>
                ");

                $datos_videos = Sentencias::Seleccionar("videos", null, null, null, null);
            }

            //Se renderiza el esquema de la tabla
            echo
            ("
                <table class='highlight centered col s12'>
                    <thead>
                        <tr>
                            <th>Descripcion</th>
                            <th>Usuario</th>
                            <th><a class='blue-text text-darken-4' href='videos.php?accion=2'>Estado de video</a></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ");

            //Se verifica si hay datos en la tabla videos
            if($datos_videos != null)
            {
                //Empieza a renderizar los datos de la tabla
                foreach($datos_videos as $row_videos)
                {
                    $estado;

                    if($row_videos["estado"] == 1)
                    {
                        $estado = "Activo";
                    }

                    if($row_videos["estado"] == 0)
                    {
                        $estado = "Inactivo";
                    }

                    $data_usuario = Sentencias::Seleccionar("usuarios", "id", array($row_videos["id_usuario"]), 0, null);

                    //Se renderiza el modal que contendra el video
                    echo
                    ("
                        <tr>
                            <td>".$row_videos['descripcion']."</td>
                            <td>".$data_usuario['usuario']."</td>
                            <td>".$estado."</td>
                            <td>
                                <a href='videos.php?id_video=".$row_videos['id']."' class='blue-text text-darken-4'><i class='material-icons'>mode_edit</i></a>
                                <a href='#modal".$row_videos['id']."' class='blue-text text-darken-4'><i class='material-icons'>visibility</i></a>
                            </td>
                        </tr>

                        <div id='modal".$row_videos['id']."' class='modal modal-fixed-footer'>
                            <div class='modal-content'>                               
                                <div class='video-container'>
                                    <iframe width='853' height='480' src='//www.youtube.com/embed/$row_videos[link]?rel=0' frameborder='0' allowfullscreen></iframe>
                                </div>      
                            </div>
                            <div class='modal-footer'>
                                <a href='#!' class='modal-action modal-close waves-effect waves-green btn-flat'>Ok</a>
                            </div>
                        </div>
                    ");
                }
            }

            echo
            ("
                            </tbody>
                        </table>
                    </div>
                </dvi>
            ");
        }
    }
?>