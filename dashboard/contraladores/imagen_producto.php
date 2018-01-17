<?php
    //Clase con las opciones para agregar imagenes a los productos
    class opcionesImagenes
    {
        //Metodo para relacionar cada imagen con cada producto
        public static function Relacionar()
        {
            //Se valida que solo se muestre cuando se seleccione un registro
            if(!empty($_GET["id_producto"]))
            {
                //Se valida que sea un numero
                if(isset($_GET["id_producto"]))
                {
                    //Se valida que ese registro exista
                    $dato_producto = Sentencias::Seleccionar("productos", "id", array($_GET["id_producto"]), 0, null);

                    if($dato_producto != null)
                    {
                        //datos de todos las imagenes añadidas
                        $datos_imagenes_productos = Sentencias::Seleccionar("imagenes_productos", null, null, null, null);

                        //Variable con los permisos del usuario
                        $dato_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

                        //variable con la id del producto
                        $id_producto = $_GET["id_producto"];

                        //Empieza a verificar para agregar las imagenes a los productos
                        if(!empty($_GET["id_imagen"]))
                        {
                            if(is_numeric($_GET["id_imagen"]))
                            {
                                //Verifica que exista el parametro de accion
                                if(!empty($_GET["accion"]))
                                {
                                    //Valida que sea un numero
                                    if(is_numeric($_GET["accion"]))
                                    {
                                        //Aqui es la opcion 1, de agregar
                                        if($_GET["accion"] == 3)
                                        {
                                            //Valida que tenga los permisos para editar
                                            if($dato_tipo["productos"] == 3 || dato_tipo["productos"] == 5 || dato_tipo["productos"] > 6)
                                            {
                                                $campos_valores = array
                                                (
                                                    'id_producto' => $_GET["id_producto"]
                                                );

                                                $condiciones_parametros = array
                                                (
                                                    'id' => $_GET["id_imagen"]
                                                );

                                                Sentencias::Actualizar("imagenes_productos", $campos_valores, $condiciones_parametros, 0, "relacionar.php?id_producto='.$id_producto.'");
                                            }

                                            else
                                            {
                                                header("Location: productos.php");
                                            }
                                        }

                                        //Aqui es la opcion 2, quitar la imagen
                                        if($_GET["accion"] == 3)
                                        {
                                            //Valida que tenga los permisos para editar
                                            if($dato_tipo["productos"] == 3 || dato_tipo["productos"] == 5 || dato_tipo["productos"] > 6)
                                            {
                                                $campos_valores = array
                                                (
                                                    'id_producto' => null
                                                );

                                                $condiciones_parametros = array
                                                (
                                                    'id' => $_GET["id_imagen"]
                                                );

                                                Sentencias::Actualizar("imagenes_productos", $campos_valores, $condiciones_parametros, 0, "relacionar.php?id_producto='.$id_producto.'");
                                            }

                                            else
                                            {
                                                header("Location: productos.php");
                                            }
                                        }

                                        //Aqui es la opcion 3, eliminar
                                        if($_GET["accion"] == 3)
                                        {
                                            //Valida que tenga los permisos para eliminar
                                            if($dato_tipo["productos"] == 4 || dato_tipo["productos"] > 5)
                                            {
                                                $condiciones_parametros = array
                                                (
                                                    'id' => $_GET["id_imagen"]
                                                );

                                                Sentencias::Eliminar("imagenes_productos", $condiciones_parametros, 1, "relacionar.php?id_producto='.$id_producto.'");
                                            }

                                            else
                                            {
                                                header("Location: productos.php");
                                            }
                                        }
                                    }

                                    else
                                    {
                                        header("Location: productos.php");
                                    }
                                }
                            }

                            else
                            {
                                header("Location: productos.php");
                            }
                        }

                        if($datos_imagenes_productos != null)
                        {
                            //Renderiza el esquema de la tabla
                            echo
                            ("
                                <div class='row'>
                                    <div class='col s12 center-align'>
                                        <h4>Lista de imagenes disponibles para los productos</h4>
                                    </div>
                                    <table class='highlight centered col s12'>
                                    <thead>
                                        <tr>
                                            <th>Imagen</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            ");   

                            foreach($datos_imagenes_productos as $row_imagenes_productos)
                            {
                                //Renderiza solo las imagenes que no tienen un producto o las imagenes de ese mismo producto
                                if($row_imagenes_productos["id_producto"] == null || $row_imagenes_productos["id_producto"] == $_GET["id_producto"])
                                {
                                    echo
                                    ("
                                        <tr>
                                            <td><img width='50' height='50' src='../img/productos/$row_imagenes_productos[imagen]'></td>
                                            <td>             
                                    ");
                                    
                                    //Aqui es donde se podra agregar esa imagen al producto seleccionado
                                    if($row_imagenes_productos["id_producto"] == null)
                                    {
                                        echo
                                        ("
                                            <a href='relacionar.php?id_producto=".$id_producto."&id_imagen=".$row_imagenes_productos['id']."&accion=1' class='blue-text text-darken-4'><i class='material-icons'>add</i></a>
                                        ");
                                    }   

                                    //Aqui es donde se eliminaran las imagenes de ese producto
                                    if($row_imagenes_productos["id_producto"] == $_GET["id_producto"])
                                    {
                                        echo
                                        ("
                                            <a href='relacionar.php?id_producto=".$id_producto."&id_imagen=".$row_imagenes_productos['id']."&accion=2' class='blue-text text-darken-4'><i class='material-icons'>remove</i></a>
                                        ");
                                    }

                                    //Renderiza la opcionde eliminar la imagen solo si tiene los permisos
                                    if($dato_tipo["productos"] == 4 || $dato_tipo["productos"] > 5)
                                    {
                                        echo
                                        ("
                                            <a href='relacionar.php?id_producto=".$id_producto."&id_imagen=".$row_imagenes_productos['id']."&accion=3' class='blue-text text-darken-4'><i class='material-icons'>delete</i></a> 
                                        ");
                                    } 
                                    echo
                                    ("                   
                                            </td>
                                        </tr>
                                    ");
                                }
                            }
                        }
                    }

                    else
                    {
                        header("Location: productos.php");
                    }
                }

                else
                {
                    header("Location: productos.php");
                }
            }

            else
            {
                header("Location: productos.php");
            }
        }
    }
?>