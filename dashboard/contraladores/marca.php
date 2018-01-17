<?php
    //clase con las opciones de marcas
    class opcionesMarcas
    {
        //metodo para renderizar los permisos de marcas
        public static function permisosMarcas()
        {
            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //se declaran las variables active
            $active1 = null;
            $active2 = null;

            //Se cambian las variables en caso de que cumpla la condicion
            if(!empty($_GET["id_marca"]))
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
            if($dato_tipo["marcas"] > 0)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active2 blue-text text-darken-4' href='#lista'>Lista de marcas</a></li>
                ");
            }

            //Renderiza cuando tiene permisos para crear y modificar
            if($dato_tipo["marcas"] == 2 || $dato_tipo["marcas"] == 3 || $dato_tipo["marcas"] > 4)
            {
                echo
                ("
                    <li class='tab col s6'><a class='$active1 blue-text text-darken-4' href='#registro'>Agregar marca</a></li>
                ");
            }

            echo
            ("
                        </ul>
                    </div>
                </div>
            ");
        }

        //Metodo para mostrar los registro de la tabla marcas
        public static function tablaMarcas()
        {
             //Comprueba si hay un registro seleccionado
            if(!empty($_GET["id_marca"]))
            {
                //Se valida que sea un numero
                if(is_numeric($_GET["id_marca"]))
                {
                    //Variable con los datos de la marca
                    $datos_marca = Sentencias::Seleccionar("marcas", "id", array($_GET["id_marca"]), 0, null);

                    //Se valida que tenga datos y que exista
                    if($datos_marca != null)
                    {
                        $marca = $datos_marca["marca"];
                    }
                }

                else
                {
                    header("Location: marcas.php");
                }
            }

            else
            {
                //$marca = null;
            }
            
            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Renderiza si tiene los permisos de lectura
            if($dato_tipo["marcas"] > 0)
            {
                //Variable con todos los registro de la tabla marcas
                $datos_marcas = null;  

                echo
                ("
                    <div class='row' id='lista'>
                        <div class='col s12'>
                            <div class='col s12 center-align'>
                                <h4>Lista de marcas ingresadas</h4>
                            </div>
                "); 

                //Se valida si se hara una busqueda
                if(!empty($_GET["accion"]))
                {
                    //Se valida si la opcion es 1
                    if($_GET["accion"] == 1)
                    {
                        echo
                        ("
                            <div class='center-align'>
                                <h4>Buscando por nombre de la marca</h4>
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
                                    <label for='buscar'>Ingrese el nombre de la marca a buscar</label>
                                </div>
                                <div class='col s4 offset-s2 l2'>
                                    <button name='busqueda' class='waves-effect waves-light btn blue darken-4'>Buscar</button>
                                </div>
                                <div class='col s4 offset-s2 l2'>
                                    <a href='marcas.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
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
                    }

                    else
                    {
                        header("Location: marcas.php");
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

                    $datos_marcas = Sentencias::Seleccionar("marcas", null, null, null, null);  
                }

                //Renderiza la tabla
                echo
                ("
                    <table class='highlight centered col s12'>
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th><a class='blue-text text-darken-4' href='marcas.php?accion=1'>Marca</a></th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                    <tbody>
                ");

                //Se valida si hay datos en la variable
                if($datos_marcas != null)
                {
                    //Empieza el foreach para mostrar las marcas
                    foreach($datos_marcas as $row_marcas)
                    {
                        echo
                        ("
                            <tr>
                                <td><img class='circle' width='50' height='50' src='../img/marcas/$row_marcas[imagen]'></td>
                                <td>".$row_marcas['marca']."</td>
                                <td><a href='marcas.php?id_marca=".$row_marcas['id']."' class='blue-text text-darken-4'><i class='material-icons'>mode_edit</i></a></td>
                            </tr>
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

        //Metodo para ingresar marcas
        public static function formularioMarcas()
        {
            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Variables de los campos de la tabla marcas
            $marca = null;

           

            //Se empiezan a validar los datos del formulario de marcas
            if(isset($_POST["formulario"]))
            {
                //Se valida que no se dejen espacios vacios
                if($_POST["marca"] != "")
                {
                    //Se valida que la marca cumpla con los criterios
                    if(Validaciones::alfanumerico($_POST["marca"]) && Validaciones::longitud($_POST["marca"], 20))
                    {
                        //Se divide en si es ingresar un registro o modificar uno existente
                        //Aqui es donde se modifica el registro
                        if(!empty($_GET["id_marca"]))
                        {
                            //Se valida si el parametro es un numero
                            if(is_numeric($_GET["id_marca"]))
                            {
                                //Se divide entre solo actualizar marca, imagen y ambas a la vez
                                //Aqui se actualiza el nombre y la imagen
                                if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
                                {
                                    $img = $_POST["marca"].time().".jpg";

                                    $campos_valores = array
                                    (
                                        'marca' => $_POST["marca"],
                                        'imagen' => $img
                                    );

                                    $condiciones_parametros = array
                                    (
                                        'id' => $_GET["id_marca"]
                                    );

                                    Sentencias::Actualizar("marcas", $campos_valores, $condiciones_parametros, 1, "marcas.php");
                                    move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/marcas/$img");
                                }

                                //Aqui solo se actualizara el nombre
                                else
                                {
                                    $campos_valores = array
                                    (
                                        'marca' => $_POST["marca"]
                                    );

                                    $condiciones_parametros = array
                                    (
                                        'id' => $_GET["id_marca"]
                                    );

                                    Sentencias::Actualizar("marcas", $campos_valores, $condiciones_parametros, 1, "marcas.php");                
                                }
                            }

                            else
                            {
                                header("Location: marcas.php");
                            }
                        }

                        //Aqui es donde se agrega un nuevo registro
                        else
                        {
                            //Se valida si ya se subio la imagen
                            if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
                            {
                                //Se validan los valores de la iamgen
                                if(Validaciones::imagen($_FILES["archivo"]))
                                {
                                    $img = $_POST["marca"].time().".jpg";
                                    $campos_valores = array
                                    (
                                        'marca' => $_POST["marca"],
                                        'imagen' => $img
                                    );

                                    Sentencias::Insertar("marcas", $campos_valores, 1, "marcas.php");
                                    move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/marcas/$img");
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "La imagen no es valida", null);
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "Debe ingresar una imagen", null);
                            }
                        }
                    }   

                    else
                    {
                        Ventanas::Mensaje(2, "El nombre de la marca no es valido", null);
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "No deje campos vacios", null);
                }
            }

            //Renderiza el formulario de marcas
            echo
            ("
                <form method='post' enctype='multipart/form-data' id='registro'>
                    <div class='row'>
                        <div class='container'>
                            <div class='col s12 center-align'>
                                <h4>Aqui puedes ingresar una nueva marca</h4>
                            </div>
                            <div class='input-field col s10 offset-s1'>
                                <input id='marca' value='$marca' name='marca' type='text' class='validate' autocomplete='off'>
                                <label for='marca' class='blue-text text-darken-4'>Nombre de la marca</label>
                            </div>
                            <div class='file-field input-field col s10 offset-s1'>
                                <div class='btn blue darken-4'>
                                    <span>Imagen</span>
                                    <input type='file' name='archivo'>
                                </div>
                                <div class='file-path-wrapper'>
                                    <input class='file-path validate' type='text' placeholder='Seleccione una imagen para el usuario'>
                                </div>
                            </div>
            ");

            //Se renderiza el boton dependiendo de si es para crear o modificar
            if(empty($_GET["id_marca"]))
            {
                //Se valida si tiene permisos para ingresar
                if($dato_tipo["marcas"] == 2 || $dato_tipo["marcas"] == 5 || $dato_tipo["marcas"] ==  6 || $dato_tipo["marcas"] ==  9)
                {
                    echo
                    ("
                        <div class='col s3 offset-s1'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Ingresar marca</button>
                        </div>  
                    ");
                }   
            }

            else
            {
                //Se valida si tiene permisos para modificar
                if($dato_tipo["marcas"] == 3 || $dato_tipo["marcas"] == 5 || $dato_tipo["marcas"] > 6)
                {
                    echo
                    ("
                        <div class='col s3 offset-s1'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Actualizar marca</button>
                        </div>  
                    ");
                }          
            }

            echo
            ("  
                            <div class='col s3'>
                                <a href='marcas.php' class='waves-effect waves-light btn blue darken-4'>Limpiar campos</a>
                            </div>   
                        </div>
                    </div>            
                </form>
            ");
        }
    }
?>