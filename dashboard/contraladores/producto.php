<?php
    //Clase con las opciones de los productos
    class opcionesProductos
    {
        //metodo para renderizar la tabla de permisos
        public static function permisosProductos()
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
            if($dato_tipo["productos"] > 0)
            {
                echo
                ("
                    <li class='tab col s4'><a class='$active2 blue-text text-darken-4' href='#lista'>Lista de productos</a></li>
                ");
            }

            //Renderiza cuando tiene permisos para crear y modificar
            if($dato_tipo["productos"] == 2 || $dato_tipo["productos"] == 3 || $dato_tipo["productos"] > 4)
            {
                echo
                ("
                    <li class='tab col s4'><a class='$active1 blue-text text-darken-4' href='#registro'>Agregar productos</a></li>
                ");
            }

            //Renderiza solo cuando tiene permisos de lectura
            if($dato_tipo["productos"] > 0)
            {
                echo
                ("
                    <li class='tab col s4'><a class='blue-text text-darken-4' href='#reporte'>Reportes y Graficos</a></li>
                ");
            }

            echo
            ("
                        </ul>
                    </div>
                </div>
            ");
        }

        //metodo para renderizar el formulario de productos
        public static function formularioProductos()
        {
            //Vairable que tendralos  datos de un producto en especifico
            $dato_producto = null;

            //Variable con todas las marcas
            $datos_marcas = Sentencias::Seleccionar("marcas", null, null, null, null);

            //Variable con todos los permisos de usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //Variables que tendran los datos del formulario
            $producto;
            $precio;
            $inventario;
            $procesador;
            $ram;
            $peso;
            $pantalla;
            $tipo_pantalla;
            $dimensiones;
            $bateria;
            $memoria;
            $ampliable;
            $camara;
            $frontal;
            $sistema;

            //Aqui se verifica si se ha seleccionado un producto a editar
            if(!empty($_GET["id_producto"]))
            {
                //Se valida que el parametro sea un numero
                if(is_numeric($_GET["id_producto"]))
                {
                    //Variable con los datos de un producto en especifico
                    $dato_producto = Sentencias::Seleccionar("productos", "id", array($_GET["id_producto"]), 0, null);

                    //Se llenan las variables para el formulario
                    $producto = $dato_producto["producto"];
                    $precio = $dato_producto["precio"];
                    $inventario = $dato_producto["inventario"];
                    $procesador = $dato_producto["procesador"];
                    $ram = $dato_producto["ram"];
                    $peso = $dato_producto["peso"];
                    $pantalla = $dato_producto["pantalla"];
                    $tipo_pantalla = $dato_producto["tipo_pantalla"];
                    $dimensiones = $dato_producto["dimensiones"];
                    $bateria = $dato_producto["bateria"];
                    $memoria = $dato_producto["memoria"];
                    $ampliable = $dato_producto["ampliable"];
                    $camara = $dato_producto["camara"];
                    $frontal = $dato_producto["frontal"];
                    $sistema = $dato_producto["sistema"];
                }

                else
                {
                    header("Location: productos.php");
                }
            }

            else
            {
                //Se llenan las variables para el formulario
                $producto = null;
                $precio = null;
                $inventario = null;
                $procesador = null;
                $ram = null;
                $peso = null;
                $pantalla = null;
                $tipo_pantalla = null;
                $dimensiones = null;
                $bateria = null;
                $memoria = null;
                $ampliable = null;
                $camara = null;
                $frontal = null;  
                $sistema = null;
            }

            //Empieza a validar los datos del formulario
            if(isset($_POST["formulario"]))
            {
                if(empty($_POST["formulario"]))
                {
                    //Se valida que no haya datos vacios
                    /*if($producto != "" && $precio != "" && $inventario != "" && $procesador != "" && $ram != "" && $peso != "" && $pantalla != "" && $tipo_pantalla != "" && $dimensiones != "" && $bateria != "" && $memoria != "" && $ampliable != "" && $camara != "" && $frontal != "" && $sistema != "")
                    {*/
                        //Se valida el producto
                        if(Validaciones::alfanumerico($producto) && Validaciones::longitud($producto, 30))
                        {
                            //VAriable con el nombre de la imagen a guardar
                            $img = $producto.time().".jpg";
                            
                            //Se valida el precio del producto
                            if(Validaciones::longitud($precio, 6) && $precio != 000.00)
                            {
                                //Se valida el inventario del producto
                                if(Validaciones::solo_numero($inventario) && Validaciones::longitud($inventario, 10) && $inventario > 0)
                                {
                                    //Se valida el procesador del producto
                                    if(Validaciones::alfanumerico($procesador) && Validaciones::longitud($procesador, 20))
                                    {
                                        //Se valida la ram del producto
                                        if(Validaciones::longitud($ram, 4))
                                        {
                                            //Se valida el peso del producto
                                            if(Validaciones::solo_numero($peso) && Validaciones::longitud($peso, 3) && $peso > 0)
                                            {
                                                //Se validan las dimensiones del producto
                                                if(Validaciones::longitud($dimensiones, 15))
                                                {
                                                    //Se valida la bateria del producto
                                                    if(Validaciones::solo_numero($bateria) && Validaciones::longitud($bateria, 4))
                                                    {
                                                        //Se validan las memorias del producto
                                                        if(Validaciones::longitud($memoria, 5) && Validaciones::longitud($ampliable, 5))
                                                        {
                                                            //Se validan las camaras del producto
                                                            if(Validaciones::longitud($camara, 4) && Validaciones::longitud($frontal, 3))
                                                            {
                                                                //Se valida el sistema del producto
                                                                if(Validaciones::longitud($sistema, 20))
                                                                {
                                                                    //Aqui se divide entre actualizar un producto o crear uno
                                                                    //Aqui se actualiza un producto
                                                                    if(!empty($_GET["id_producto"]))
                                                                    {
                                                                        //Se valida que el parametro sea un numero
                                                                        if(is_numeric($_GET["id_producto"]))
                                                                        {
                                                                            //Aqui se divide en actualizar con o sin imagen
                                                                            //Aqui se actualiza con imagen
                                                                            if(is_uploaded_file($_FILES["archivo"]["tmp_name"]))
                                                                            {
                                                                                if(Validaciones::imagen($_FILES["archivo"]))
                                                                                {
                                                                                    $campos_valores = array
                                                                                    (
                                                                                        'producto' => $producto,
                                                                                        'precio' => $precio,
                                                                                        'inventario' => $inventario,
                                                                                        'procesador' => $procesador,
                                                                                        'ram' => $ram,
                                                                                        'pantalla' => $pantalla,
                                                                                        'tipo_pantalla' => $tipo_pantalla,
                                                                                        'peso' => $peso,
                                                                                        'dimensiones' => $dimensiones,
                                                                                        'memoria' => $memoria,
                                                                                        'ampliable' => $ampliable,
                                                                                        'bateria' => $bateria,
                                                                                        'camara' => $camara,
                                                                                        'frontal' => $frontal,
                                                                                        'sistema' => $sistema,
                                                                                        'imagen' => $img,
                                                                                        'id_marca' => $_POST["marca"],
                                                                                        'id_usuario' => $_SESSION["id"]
                                                                                    );

                                                                                    $condiciones_parametros = array
                                                                                    (
                                                                                        'id' => $_GET["id_producto"]
                                                                                    );

                                                                                    Sentencias::Actualizar("productos", $campos_valores, $condiciones_parametros, 1, "productos.php");
                                                                                    move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/productos/$img");
                                                                                }

                                                                                else
                                                                                {
                                                                                    Ventanas::Mensaje(2, "La imagen del producto no es valida", null);
                                                                                }
                                                                            }

                                                                            //Aqui se actualiza sin imagen
                                                                            else
                                                                            {
                                                                                $campos_valores = array
                                                                                (
                                                                                    'producto' => $producto,
                                                                                    'precio' => $precio,
                                                                                    'inventario' => $inventario,
                                                                                    'procesador' => $procesador,
                                                                                    'ram' => $ram,
                                                                                    'pantalla' => $pantalla,
                                                                                    'tipo_pantalla' => $tipo_pantalla,
                                                                                    'peso' => $peso,
                                                                                    'dimensiones' => $dimensiones,
                                                                                    'memoria' => $memoria,
                                                                                    'ampliable' => $ampliable,
                                                                                    'bateria' => $bateria,
                                                                                    'camara' => $camara,
                                                                                    'frontal' => $frontal,
                                                                                    'sistema' => $sistema,
                                                                                    'id_marca' => $_POST["marca"],
                                                                                    'id_usuario' => $_SESSION["id"]
                                                                                );

                                                                                $condiciones_parametros = array
                                                                                (
                                                                                    'id' => $_GET["id_producto"]
                                                                                );

                                                                                Sentencias::Actualizar("productos", $campos_valores, $condiciones_parametros, 1, "productos.php");
                                                                            }
                                                                        }

                                                                        else
                                                                        {
                                                                            header("Location: productos.php");
                                                                        }
                                                                    }

                                                                    //Aqui se crea un nuevo producto
                                                                    else
                                                                    {
                                                                        if(Validaciones::imagen($_FILES["archivo"]))
                                                                        {
                                                                            $campos_valores = array
                                                                            (
                                                                                'producto' => $producto,
                                                                                'precio' => $precio,
                                                                                'inventario' => $inventario,                                                                
                                                                                'procesador' => $procesador,
                                                                                'ram' => $ram,
                                                                                'pantalla' => $pantalla,
                                                                                'tipo_pantalla' => $tipo_pantalla,
                                                                                'peso' => $peso,
                                                                                'dimensiones' => $dimensiones,
                                                                                'memoria' => $memoria,
                                                                                'ampliable' => $ampliable,
                                                                                'bateria' => $bateria,
                                                                                'camara' => $camara,
                                                                                'frontal' => $frontal,
                                                                                'sistema' => $sistema,
                                                                                'imagen' => $img,
                                                                                'id_marca' => $_POST["marca"],
                                                                                'id_usuario' => $_SESSION["id"]
                                                                            );

                                                                            Sentencias::Insertar("productos", $campos_valores, 1, "productos.php");
                                                                            move_uploaded_file($_FILES['archivo']['tmp_name'], "../img/productos/$img");
                                                                        }

                                                                        else
                                                                        {
                                                                            Ventanas::Mensaje(2, "La imagen de producto no es valida", null);
                                                                        }
                                                                    }
                                                                }

                                                                else
                                                                {
                                                                    Ventanas::Mensaje(2, "El sistema del producto no es valido", null);
                                                                }
                                                            }

                                                            else
                                                            {
                                                                Ventanas::Mensaje(2, "Las camaras del producto no son validas", null);
                                                            }
                                                        }

                                                        else
                                                        {
                                                            Ventanas::Mensaje(2, "Las memorias del producto no son validas", null);
                                                        }
                                                    }

                                                    else
                                                    {
                                                        Ventanas::Mensaje(2, "La bateria del producto no es valida", null);
                                                    }
                                                }

                                                else
                                                {
                                                    Ventanas::Mensaje(2, "Las dimensiones del producto no son validas", null);
                                                }
                                            }

                                            else
                                            {
                                                Ventanas::Mensaje(2, "El peso del producto no es valido", null);
                                            }
                                        }

                                        else
                                        {
                                            Ventanas::Mensaje(2, "La ram del producto no es valida", null);  
                                        }
                                    }

                                    else
                                    {
                                        Ventanas::Mensaje(2, "El procesador del producto no es valido", null);  
                                    }
                                }

                                else
                                {
                                    Ventanas::Mensaje(2, "El inventario del producto no es valido", null);   
                                }
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "El precio del producto no es valido", null);
                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El nombre del producto no es valido $producto hola", null);
                        }
                    //}

                    /*else
                    {
                        Ventanas::Mensaje(2, "No deje campos vacios", null);
                    }*/
                }

                else
                {
                    Ventanas::Mensaje(2, "Que putas pasa?", null);    
                }
            }

            //Aqui renderiza el formualrio
            echo
            ("
                <form method='post' enctype='multipart/form-data' id='registro'>
                    <div class='row'>
                        <div class='container'>
                            <div class='col s12 l10 offset-l1 center-align'>
                                <h4>Aqui puedes  ingresar un nuevo producto</h4>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='producto' value='$producto' name='producto' type='text' data-length='30'>
                                <label for='producto' class='blue-text text-darken-4'>Producto</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='precio'value='$precio' name='precio' type='text' data-length='6'>
                                <label for='precio' class='blue-text text-darken-4'>Precio</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='inventario' value='$inventario' name='inventario' type='text' data-length='10'>
                                <label for='inventario' class='blue-text text-darken-4'>Inventario</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='procesador' value='$procesador' name='procesador' type='text' data-length='20'>
                                <label for='procesador' class='blue-text text-darken-4'>Procesador</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='ram' value='$ram' name='ram' type='text' data-length='4'>
                                <label for='ram' class='blue-text text-darken-4'>Ram</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='peso' value='$peso' name='peso' type='text' data-length='3'>
                                <label for='peso' class='blue-text text-darken-4'>Peso</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='pantalla' value='$pantalla' name='pantalla' type='text' data-length='3'>
                                <label for='pantalla' class='blue-text text-darken-4'>Pantalla</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='tipo_pantalla' value='$tipo_pantalla' name='tipo_pantalla' type='text' data-length='20'>
                                <label for='tipo_pantalla' class='blue-text text-darken-4'>Tipo Pantalla</label>
                            </div>     
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='dimensiones' value='$dimensiones' name='dimensiones' type='text' data-length='15'>
                                <label for='dimensiones' class='blue-text text-darken-4'>Dimensiones</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='bateria' value='$bateria' name='bateria' type='text' data-length='4'>
                                <label for='bateria' class='blue-text text-darken-4'>Bateria</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='memoria' value='$memoria' name='memoria' type='text' data-length='5'>
                                <label for='memoria' class='blue-text text-darken-4'>Memoria</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='ampliable' value='$ampliable' name='ampliable' type='text' data-length='5'>
                                <label for='ampliable' class='blue-text text-darken-4'>Memoria Ampliable</label>
                            </div>                  
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='camara' value='$camara' name='camara' type='text' data-length='4'>
                                <label for='camara' class='blue-text text-darken-4'>Camara</label>
                            </div>
                            <div class='input-field col s10 offset-s1 l6'>
                                <input id='frontal' value='$frontal' name='frontal' type='text' data-length='3'>
                                <label for='frontal' class='blue-text text-darken-4'>Frontal</label>
                            </div>
            ");   

            //Se coloca el combobox de las marcas y el estado
            //Aqui se coloca cuando se haya seleccionado un producto a modificar
            if(!empty($_GET["id_producto"]))
            {
                echo
                ("
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='marca'>
                ");

                //Se agrega el combobox de marcas
                foreach($datos_marcas as $row_marcas)
                {
                    if($datos_marcas["id"] == $dato_producto["id_marca"])
                    {
                        echo
                        ("
                            <option value='$row_marcas[id]' selected>".$row_marcas['marca']."</option>
                        ");
                    }

                    else
                    {
                        echo
                        ("
                            <option value='$row_marcas[id]'>".$row_marcas['marca']."</option>
                        ");
                    } 
                }

                echo
                ("
                        </select>
                    </div>
                ");
            }

            //Aqui se coloca de forma predeterminada
            else
            {
                echo
                ("
                    <div class='input-field col s10 offset-s1 l6 blue-text text-darken-4'>
                        <select name='marca'>
                ");

                foreach($datos_marcas as $row_marcas)
                {
                    echo
                    ("
                        <option value='$row_marcas[id]'>".$row_marcas['marca']."</option>
                    ");
                }

                echo
                ("
                        </select>
                    </div>
                ");
            }

            //Se cola el campo para sistema y agregar imagen
            echo
            ("
                <div class='input-field col s10 offset-s1 l6'>
                    <input id='sistema' value='$sistema' name='sistema' type='text' data-length='20'>
                    <label for='sistema' class='blue-text text-darken-4'>Sistema</label>
                </div>
                <div class='file-field input-field col s10 offset-s1 l12'>
                    <div class='btn blue darken-4'>
                        <span>Imagen</span>
                        <input type='file' name='archivo'>
                    </div>
                    <div class='file-path-wrapper'>
                        <input class='file-path validate' type='text' placeholder='Seleccione una imagen para el producto'>
                    </div>
                </div>
            "); 

            //Se agragegan los botones dependiendo si esta actualizando o creando
            //Aqui se coloca cuando se esta actualizando el producto
            if(!empty($_GET["id_producto"]))
            {
                //Renderiza solo si tiene los permisos para modificar
                if($dato_tipo["productos"] == 3 || $dato_tipo["productos"] == 5 || $dato_tipo["productos"] > 6)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Modificar producto</button>
                        </div>
                    ");
                }
            }

            //Aqui renderiza para agregar un producto
            else
            {
                //Renderiza solo si tiene permisos para agregar
                if($dato_tipo["productos"] == 2 || $dato_tipo["productos"] == 5 || $dato_tipo["productos"] == 6 || $dato_tipo["productos"] == 9)
                {
                    echo
                    ("
                        <div class='col s4 offset-s1 l4'>
                            <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Agregar producto</button>
                        </div>
                    ");
                }
            }

            echo
            ("
                            <div class='col s4 offset-s1 l4'>
                                <a href='productos.php' class='waves-effect waves-light btn blue darken-4'>Limpiar campos</a>
                            </div>
                        </div>
                    </div>
                </form>
            ");
        }

        //metodo para la tabla de productos
        public static function tablaProductos()
        {
            //Variable que tendra los productos dependiendo de los parametros de busqueda
            $datos_productos = null;

            //Variable con los permisos del usuario
            $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            echo
            ("
                <div class='row' id='lista'>
                    <div class='col s12 center-align'>
                        <h4>Lista de productos registrados</h4>
            ");

            //Aqui se colocan los formularios para parametrizar las busquedas
            //Aqui se verifica si se ha selccionado una opcion
            if(!empty($_GET["accion"]))
            {
                if($_GET["accion"] == 1)
                {
                    //Renderiza el formulario de busqueda
                    echo
                    ("
                        <div class='center-align'>
                            <h4>Buscando por nombre del producto</h4>
                        </div>
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
                                <a href='productos.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                            </div>
                        </form>
                    ");

                    //Verifica si se ha presionado el boton de busqueda
                    if(isset($_POST["busqueda"]))
                    {
                        if(!empty($_POST["buscar"]))
                        {
                            $busqueda = $_POST["buscar"];

                            if(Validaciones::alfanumerico($busqueda) && Validaciones::longitud($busqueda, 20))
                            {
                                $datos_productos = Sentencias::Seleccionar("productos", "producto", array($busqueda), 1, 1);
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "e¿El paremetro de busqueda no es valido", null);
                            }
                        }
                    }
                }

                if($_GET["accion"] == 5)
                {
                    echo
                    ("
                        <div class='center-align'>
                            <h4>Buscando por estado del producto</h4>
                        </div>
                        <div class='col s3 offset-s1'>
                            <a href='productos.php?accion=5&estado=1' class='waves-effect waves-light btn blue darken-4'>Activo</a>
                        </div>
                        <div class='col s3'>
                            <a href='productos.php?accion=5&estado=2' class='waves-effect waves-light btn blue darken-4'>Inactivo</a>
                        </div>
                        <div class='col s3'>
                            <a href='productos.php' class='waves-effect waves-light btn blue darken-4'>Limpiar</a>
                        </div>
                    ");


                    if(!empty($_GET["estado"]))
                    {
                        if($_GET["estado"] == 1)
                        {
                            $datos_productos = Sentencias::Seleccionar("productos", "estado", array(1), 1, 1);
                        }

                        if($_GET["estado"] == 2)
                        {
                            $datos_productos = Sentencias::Seleccionar("productos", "estado", array(0), 1, 1);
                        }
                    }
                }
            }

            //Aqui toma los valores por defecto
            else
            {
                echo
                ("
                    <div class='center-align'>
                        <h4>Selecciona una opcion de busqueda en la tabla</h4>
                    </div>
                ");

                $datos_productos = Sentencias::Seleccionar("productos", null, null, null, null);
            }

            //Renderiza el esquema de la tabla
            echo
            ("
                <table class='highlight centered col s12'>
                    <thead>
                        <tr>
                            <th><a class='blue-text text-darken-4' href='productos.php?accion=1'>Producto</a></th>
                            <th>Precio</th>
                            <th>Inventario</th>
                            <th>Marca</th>
                            <th>Usuario que lo ingreso</th>
                            <th><a class='blue-text text-darken-4' href='productos.php?accion=5'>Estado</a></th>
                            <th>Comentarios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ");

            //Aqui se renderizara en base a la variable con los datos
            //Se verifica que no este vacia
            if($datos_productos != null)
            {
                //Se renderizan todos los datos
                foreach($datos_productos as $row_productos)
                {
                    $estado_producto;

                    if($row_productos["estado"] == 1)
                    {
                        $estado_producto = "Activado";
                    }

                    if($row_productos["estado"] == 0)
                    {
                        $estado_producto = "Desactivado";
                    }

                    //Variable que tiene la marca a la que el producto pertenece
                    $dato_marca = Sentencias::Seleccionar("marcas", "id", array($row_productos["id_marca"]), 1, null);

                    //Variable que tiene el usuario que añadio/modifico el producto
                    $dato_usuario = Sentencias::Seleccionar("usuarios", "id", array($row_productos["id_usuario"]), 1, null);

                    //Se rederizan los datos en la tabla ademas de añadirse el modal con la informacion
                    echo
                    ("
                        <tr>
                            <td>".$row_productos['producto']."</td>
                            <td>$".$row_productos['precio']."</td>
                            <td>".$row_productos['inventario']."</td>
                            <td>".$row_productos["id_usuario"]/*$dato_marca['marca']*/."</td>
                            <td>".$row_productos["id_usuario"]/*$dato_usuario['usuario']*/."</td>               
                            <td>".$estado_producto."</td>
                            <td><a href='reportes/comentarios_producto.php?id_producto=".$row_productos['id']."' class='blue-text text-darken-4'>Reporte<i class='material-icons'>page</i></a></td>
                            <td>
                    ");

                    //Renderiza la opcion para modificar solo si tiene los permisos
                    if($dato_tipo["productos"] == 3 || $dato_tipo["productos"] == 5 || $dato_tipo["productos"] > 6)
                    {
                        echo
                        ("
                            <a href='productos.php?id_producto=".$row_productos['id']."' class='blue-text text-darken-4'><i class='material-icons'>mode_edit</i></a>
                        ");
                    }

                    //Se agrega el modal
                    echo
                    ("
                                <a href='#modal".$row_productos['id']."' class='blue-text text-darken-4'><i class='material-icons'>visibility</i></a> 
                                <a href='relacionar.php?id_producto=".$row_productos['id']."' class='blue-text text-darken-4'><i class='material-icons'>image</i></a>
                            </td>
                        </tr>

                        <div id='modal".$row_productos['id']."' class='modal modal-fixed-footer'>
                            <div class='modal-content'>
                                <h4>Especificaciones del ".$row_productos['producto']."</h4>
                                <p>Procesador: ".$row_productos['procesador']."</p>
                                <p>Ram: ".$row_productos['ram']." GB</p>
                                <p>Tamaño pantalla: ".$row_productos['pantalla']." Pulgadas</p>
                                <p>Tipo de pantalla: ".$row_productos['tipo_pantalla']."</p>
                                <p>Peso: ".$row_productos['peso']."Gramos</p>
                                <p>Dimensiones: ".$row_productos['dimensiones']."</p>
                                <p>Memoria: ".$row_productos['memoria']." GB</p>
                                <p>Ampliable: ".$row_productos['ampliable']." GB</p>
                                <p>Bateria: ".$row_productos['bateria']." Mah</p>
                                <p>Camara: ".$row_productos['camara']." Mp</p>
                                <p>Camara frontal: ".$row_productos['frontal']." Mp</p>
                                <p>Sistema operativo: ".$row_productos['sistema']."</p>
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

        //metodo para renderizar los botones del producto
        public static function reportes()
        {
            echo
            ("
                <div class='row' id='reporte'>
            ");

            //Renderiza botonos para visualizar los pdf
            echo
            ("
                <a href='reportes/productos_agregados.php' class='blue-text text-darken-4'>Reporte de productos agregados este mes</a>
                <br>
                <a href='reportes/ventas_mes.php' class='blue-text text-darken-4'>Reporte de ventas este mes</a>
                <br>
                <a href='graficos/mas_vendidos.php' class='blue-text text-darken-4'>Grafico con los productos mas vendidos</a>
                <br>
                <a href='graficos/mas_comentados.php' class='blue-text text-darken-4'>Grafico con los productos mas comentados</a>
                <br>
                <a href='graficos/ventas.php' class='blue-text text-darken-4'>Grafico con las ventas por mes</a>
                <br>
                <a href='graficos/clientes_comentados.php' class='blue-text text-darken-4'>Grafico con los clientes que mas comentan</a>
                <br>
                <a href='graficos/clientes_compras.php' class='blue-text text-darken-4'>Grafico con los clientes que mas compran</a>
            ");     

            echo
            ("
                </div>
            ");
        }
    }
?>