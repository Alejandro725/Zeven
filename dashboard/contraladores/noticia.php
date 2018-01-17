<?php
    //clase con las opciones de las noticias
    class opcionesNoticias
    {
        //Metodo para la tabla de permisos de noticias
        public static function permisosNoticias()
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
            if($dato_tipo["noticias"] > 0)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active2 blue-text text-darken-4' href='#lista'>Lista de noticias</a></li>
                ");
            }

            //Renderiza cuando tiene permisos para crear y modificar
            if($dato_tipo["noticias"] == 2 || $dato_tipo["noticias"] == 3 || $dato_tipo["noticias"] > 4)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active1 blue-text text-darken-4' href='#registro'>Agregar noticias</a></li>
                ");
            }

            echo
            ("
                        </ul>
                    </div>
                </div>
            ");
        }

        //metodo para el formulario de noticias
        public static function formularioNoticias()
        {
            //Variable con los datos de una noticia en concreto
            $dato_noticia = null;

            //variable que se usara en el formulario
            $titulo;
            $noticia;

            //Verifica si se ha seleccionado una noticia a modificar
            if(!empty($_GET["id_noticia"]))
            {
                if(is_numeric($_GET["id_noticia"]))
                {
                    $dato_noticia = Sentencias::Seleccionar("noticias", "id", array($_GET["id_noticia"]), 0, null);

                    $titulo = $dato_noticia["titulo"];
                    $noticia = $dato_noticia["noticia"];
                }

                else
                {
                    header("Location: noticias.php");
                }
            }

            else
            {
                $titulo = null;
                $noticia = null;
            }

            //aqui se renderizan las opciones dependiendo de los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Aqui empieza a validar los datos para la noticia
            if(isset($_POST["formulario"]))
            {   
                //Se valida que el formulario no este vacio
                if(!empty($_POST))
                {
                    //Aqui se llenan las variables con los datos del formulario
                    $titulo = $_POST["titulo"];
                    $noticia = $_POST["noticia"];
                    $estado = $_POST["estado"];
                    $img = $titulo.time().".jpg";
                    $hoy = getdate();
                    $fecha = $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];

                    //Aqui se valida que ningun dato este vacio
                    if($titulo !="" && $noticia != "")
                    {
                        //Se verifica que el titulo sea valido
                        if(Validaciones::alfanumerico($titulo) && Validaciones::longitud($titulo, 30))
                        {
                            //Se valida que la noticia sea valida
                            if(Validaciones::alfanumerico($noticia) && Validaciones::longitud($noticia, 200))
                            {
                                //Se divide en si actualizar la noticia o crear una nueva
                                //Aqui actualiza la noticia
                                if(!empty($_GET["id_noticia"]))
                                {
                                    //Se valida que el parametro dado sea numerico
                                    if(is_numeric($_GET["id_noticia"]))
                                    {
                                        //Se divide en si actualizara con o sin imagen
                                        //Aqui se actualiza con imagen
                                        if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
                                        {
                                            if(Validaciones::imagen($_FILES["archivo"]))
                                            {
                                                $campos_valores = array
                                                (
                                                    'titulo' => $titulo,
                                                    'noticia' => $noticia,
                                                    'fecha' => $fecha,
                                                    'imagen' => $img,
                                                    'id_usuario' => $_SESSION["id"],
                                                    'estado' => $estado
                                                );

                                                $condiciones_parametros = array
                                                (
                                                    'id' => $_GET["id_noticia"]
                                                );

                                                Sentencias::Actualizar("noticias", $campos_valores, $condiciones_parametros, 1, "noticias.php");
                                                move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/noticias/$img");
                                            }
                                            
                                            else
                                            {
                                                Ventanas::Mensaje(2, "La imagen no es valida", null);
                                            }
                                        }

                                        //Aqi actualiza sin imagen
                                        else
                                        {
                                            $campos_valores = array
                                            (
                                                'titulo' => $titulo,
                                                'noticia' => $noticia,
                                                'fecha' => $fecha,
                                                'id_usuario' => $_SESSION["id"],
                                                'estado' => $estado
                                            );

                                            $condiciones_parametros = array
                                            (
                                                'id' => $_GET["id_noticia"]
                                            );

                                            Sentencias::Actualizar("noticias", $campos_valores, $condiciones_parametros, 1, "noticias.php");
                                        }
                                    }

                                    else
                                    {
                                        header("Location: noticias.php");
                                    }
                                }

                                //Aqui se crea una noticia
                                else
                                {
                                    //Se valida que haya imagen
                                    if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
                                    {
                                        if(Validaciones::imagen($_FILES["archivo"]))
                                        {
                                            $campos_valores = array
                                            (
                                                'titulo' => $titulo,
                                                'noticia' => $noticia,
                                                'fecha' => $fecha,
                                                'imagen' => $img,
                                                'id_usuario' => $_SESSION["id"],
                                                'estado' => $estado
                                            );

                                            Sentencias::Insertar("noticias", $campos_valores, 1, "noticias.php");
                                            move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/noticias/$img");
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "La imagen no es valida", null);
                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "Debes ingresar una imagen", null);
                                    }
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "La noticia no es valida", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El tiutulo no es valido", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "No deje campos vacios", null);
                    }
                }
            }

            //empieza a renderizar el formulario
            echo
            ("
                <form method='post' enctype='multipart/form-data' id='registro'>
                    <div class='row'>
                        <div class='container'>    
                            <div class='col s12 l10 offset-l1 center-align'>
                                <h4>Ingresa una nueva noticia</h4>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='titulo' value='$titulo' name='titulo' type='text' class='validate' data-length='30'>
                                <label for='titulo' class='blue-text text-darken-4'>Titulo</label>
                            </div>   
            ");

            //Aqui se renderiza el combobox dependiendo si ya se ha seleccionado una noticia o no
            //Aqui es cuando se ha seleccionado una noticia
            if(!empty($_GET["id_noticia"]))
            {
                //Aqui renderiza cuando la noticia esta activa
                if($dato_noticia["estado"] == 1)
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

                //Aqui renderiza cuando la noticia esta desactivada
                if($dato_noticia["estado"] == 0)
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

            //Aqui se renderiza con valores por defecto
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

            echo
            ("        
                <div class='input-field col s10 offset-s1 l12'>
                    <input id='noticia' value='$noticia' name='noticia' type='text' class='validate' data-length='200'>
                    <label for='noticia' class='blue-text text-darken-4'>Noticia</label>
                </div>      
                <div class='file-field input-field col s10 offset-s1 l12'>
                    <div class='btn blue darken-4'>
                        <span>Imagen</span>
                        <input type='file' name='archivo'>
                    </div>
                    <div class='file-path-wrapper'>
                        <input class='file-path validate' type='text' placeholder='Seleccione una imagen para la noticia'>
                    </div>
                </div> 
            ");      

            //Renderiza el boton de ingresar/actualizar dependiendo de si se ha seleccionado antes una noticia o no
            //Aqui renderiza cuando se ha seleccionado una noticia
            if(!empty($_GET["id_noticia"]))
            {
                //Renderiza solo si tiene los permisos para modificar
                if($dato_tipo["noticias"] == 3 || $dato_tipo["noticias"] == 5 || $dato_tipo["noticias"] > 6)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Modificar noticia</button>
                        </div>
                    ");
                }
            }

            //Aqui renderiza para agregar una noticia
            else
            {
                //Renderiza solo si tiene permisos para agregar
                if($dato_tipo["noticias"] == 2 || $dato_tipo["noticias"] == 5 || $dato_tipo["noticias"] == 6 || $dato_tipo["noticias"] == 9)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Agregar noticia</button>
                        </div>
                    ");
                }
            }

            echo
            ("              
                            <div class='col s4 offset-s1 l4'>
                                <a href='noticias.php' class='waves-effect waves-light btn blue darken-4'>Limpiar campos</a>
                            </div>
                        </div>
                    </div>
                </form>
            ");
        }

        //metodo para la tabla en donde se muestran los registros
        public static function tablaNoticias()
        {
            //Variable que tendra las noticias dependiendo del parametro de busqueda
            $datos_noticias = null;

            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            echo
            ("
                <div class='row' id='lista'>
                    <div class='col s12 ' id='ver'>
                        <div class='col s12 center-align'>
                            <h4>Lista de noticias</h4>
                        </div>
            ");   

            //Se valida si hay una opcion de busqueda seleccionada
            if(!empty($_GET["accion"]))
            {
                //Aqui es la opcion cuando es 1 BUSCAR POR TITULO
                if($_GET["accion"] == 1)
                {
                    //Renderiza el formulario de busqueda
                    echo
                    ("
                        <div class='center-align'>
                            <h4>Buscando por el titulo de la noticia</h4>
                        </div>
                        <form method='post' class='col s10 offset-s1 m12'>

                            <!--Se crea la barra de busqueda-->
                            <div class='input-field col s8'>
                                <i class='material-icons prefix'>search</i>
                                <input id='buscar' type='text' class='validate' name='buscar'>
                                <label for='buscar'>Ingrese el titulo de la noticia a buscar</label>
                            </div>
                            <div class='col s4 offset-s2 l2'>
                                <button name='busqueda' class='waves-effect waves-light btn blue darken-4'>Buscar</button>
                            </div>
                            <div class='col s4 offset-s2 l2'>
                                <a href='noticias.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                            </div>
                        </form>
                    ");

                    //Valida que se use el boton de la busqueda
                    if(isset($_POST["busqueda"]))
                    {
                        if(!empty($_POST["buscar"]))
                        {
                            $busqueda = $_POST["buscar"];

                            if(Validaciones::longitud($busqueda, 20))
                            {
                                $datos_noticias = Sentencias::Seleccionar("noticias", "titulo", array("$busqueda%"), 1, 1);
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "el paremetro de busqueda no es valido", null);
                            }
                        }
                    }          
                }

                //Aqui es la opcion 3 BUSCAR POR ESTADO
                if($_GET["accion"] == 3)
                {
                    echo
                    ("
                        <div class='center-align'>
                            <h4>Buscando por el estado de la noticia</h4>
                        </div>
                        <div class='col s2 offset-s3'>
                            <a href='oticias?accion=3&estado=1.php' class='waves-effect waves-light btn blue darken-4'>Activas</a>
                        </div>
                        <div class='col s2'>
                            <a href='noticias.php?accion=3&estado=2' class='waves-effect waves-light btn blue darken-4'>Inactivas</a>
                        </div>
                        <div class='col s2'>
                            <a href='noticias.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                        </div>
                    ");

                    if(!empty($_GET["estado"]))
                    {
                        if($_GET["estado"] == 1)
                        {
                            $datos_noticias = Sentencias::Seleccionar("noticias", "estado", array(1), 1, 1);
                        }

                        if($_GET["estado"] == 2)
                        {
                            $datos_noticias = Sentencias::Seleccionar("noticias", "estado", array(0), 1, 1);
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

                $datos_noticias = Sentencias::Seleccionar("noticias", null, null, null, null);
            }

            ///Se renderiza el esquema de la tabla
            echo
            ("
                <table class='highlight centered col s12'>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th><a class='blue-text text-darken-4' href='noticias.php?accion=1'>Titulo</a></th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th><a class='blue-text text-darken-4' href='noticias.php?accion=3'>Estado</a></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ");

            //Se empiezan a renderizar los datos de la base
            if($datos_noticias != null)
            {
                //Renderiza los datos de la base en la tabla
                foreach($datos_noticias as $row_noticias)
                {
                    $estado_noticia;

                    if($row_noticias["estado"] == 1)
                    {
                        $estado_noticia =  "Activo";
                    }

                    if($row_noticias["estado"] == 0)
                    {
                        $estado_noticia = "Desactivado";
                    }

                    $dato_usuario = Sentencias::Seleccionar("usuarios", "id", array($row_noticias["id_usuario"]), 1, null);

                    //Coloca los datos de la base en la tabla
                    echo
                    ("              
                                
                        <tr>
                            <td><img width='50' height='50' src='../img/noticias/$row[imagen]'></td>
                            <td>".$row_noticias['titulo']."</td>
                            <td>".$row_noticias['fecha']."</td>
                            <td>".$data_usuario['usuario']."</td>
                            <td>".$estado_noticia."</td>
                            <td>
                    ");

                    //Se agrega la opcion de actualizar solo si cumple los permisos
                    if($dato_tipo["noticias"] == 3 || $dato_tipo["noticias"] == 5 || $dato_tipo["noticias"] > 6)
                    {
                        echo
                        ("
                            <a href='noticias.php?id_noticia=".$row_noticias['id']."' class='blue-text text-darken-4'><i class='material-icons'>mode_edit</i></a>
                        ");
                    }

                    //Crea un modal donde se vera mas a detalle cada noticia
                    echo
                    ("
                                <a href='#modal".$row_noticias['id']."' class='blue-text text-darken-4'><i class='material-icons'>visibility</i></a>
                            </td>
                        </tr>

                        <div id='modal".$row_noticias['id']."' class='modal modal-fixed-footer'>
                            <div class='modal-content center-align'>
                                <h4>Titulo: ".$row_noticias['titulo']."</h4>
                                <p>".$row_noticias['noticia']."</p>
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
                </div>
            ");
        }
    }
?>