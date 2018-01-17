<?php
    //clase para las opciones de la tienda
    class opcionesTienda
    {
        //Metodo para mostrar los productos
        public static function renderProductos()
        {
            //Se verifica si hay una opcion de busqueda
            if(isset($_POST["formulario"]))
            {
                //se verifica que el formulario no este vacio
                if(!empty($_POST))
                {
                    //Se verifica si ha ingresado un parametro de busqueda
                    if($_POST["busqueda"] != "")
                    {
                        //se declaran las variables del formulario
                        $busqueda = trim($_POST["buscar"]);
                        $orden = $_POST["orden"];

                        //Se crea un switch para el orden en el que se mostrara
                        switch ($orden)
                        {
                            //Aqui se buscara en orden predeterminado
                            case 0:
                                //Variable con los datos parametrisados
                                $datos_productos = Sentencias::Seleccionar("productos", "producto", array($busqueda), 1, 1);

                                //Verifica que existan productos con ese nombre para mostrarlos
                                if($datos_productos != null)
                                {
                                    foreach($datos_productos as $row_productos)
                                    {
                                        self::Tarjeta($row_productos);
                                    }
                                }

                                else
                                {

                                }
                                break;

                            //Aqui se buscara en orden de lo mas economico
                            case 1:
                                //Variable con los datos parametrisados
                                $datos_productos = Sentencias::Buscar("productos", "producto", array($busqueda), 1, 1, 1);

                                //Verifica que existan productos con ese nombre para mostrarlos
                                if($datos_productos != null)
                                {
                                    foreach($datos_productos as $row_productos)
                                    {
                                        self::Tarjeta($row_productos);
                                    }
                                }

                                else
                                {

                                }
                                break;

                            //Aqui se buscara en orden de lo mas costoso
                            case 2:
                                //Variable con los datos parametrisados
                                $datos_productos = Sentencias::Buscar("productos", "producto", array($busqueda), 1, 1, 0);

                                //Verifica que existan productos con ese nombre para mostrarlos
                                if($datos_productos != null)
                                {
                                    foreach($datos_productos as $row_productos)
                                    {
                                        self::Tarjeta($row_productos);
                                    }
                                }

                                else
                                {

                                }
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    }  

                    //Aqui solo se ordenara cuando no hayan aprametros de busqueda
                    else
                    {
                        $busqueda  = null;
                        $orden = $_POST["orden"];

                        switch ($orden) 
                        {
                            //Aqui es el orden predeterminado
                            case 0:
                                //Variable con los datos ordenados de forma predeterminada
                                $datos_productos = Sentencias::Seleccionar("productos", null, null, null, null);

                                //Verifica que existan productos
                                if($datos_productos != null)
                                {
                                    foreach($datos_productos as $row_productos)
                                    {
                                        self::Tarjeta($row_productos);
                                    }
                                }

                                else
                                {

                                }
                                break;

                            //Aqui es orden de lo mas economico
                            case 1:
                                //Variable con los datos ordenados de forma predeterminada
                                $datos_productos = Sentencias::Buscar("productos", null, null, null, null, 1);

                                //Verifica que existan productos
                                if($datos_productos != null)
                                {
                                    foreach($datos_productos as $row_productos)
                                    {
                                        self::Tarjeta($row_productos);
                                    }
                                }

                                else
                                {

                                }
                                break;

                            //Aqui es orden de lo mas costoso
                            case 2:
                                //Variable con los datos ordenados de forma predeterminada
                                $datos_productos = Sentencias::Buscar("productos", null, null, null, null, 0);

                                //Verifica que existan productos
                                if($datos_productos != null)
                                {
                                    foreach($datos_productos as $row_productos)
                                    {
                                        self::Tarjeta($row_productos);
                                    }
                                }

                                else
                                {

                                }
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    }              
                }
            }

            else
            {
                //variable con los datos de todos los productos
                $datos_productos = Sentencias::Seleccionar("productos", null, null, null, null);

                //se verifica que existan productos
                if($datos_productos != null)
                {
                    //Se renderizan todos los registros de la tabla productos
                    foreach($datos_productos as $row_productos)
                    {
                        self::Tarjeta($row_productos);
                    }
                }

                else
                {
                    
                }
            }
        }

        //Metodo para añadir un producto al carrito
        public static function Añadir()
        {
            //Verifica que se ha seleccionado un producto
            if(!empty($_GET["id_producto"]))
            {
                //Se valida que el parametro sea de tipo numerico
                if(is_numeric($_GET["id_producto"]))
                {
                    //Verifica que el usuario este registrado para añadir el producto
                    if(!empty($_SESSION["id_cliente"]))
                    {
                        //Variable en donde se buscan los detalles pedidos del usuario
                        $datos_pedidos = Sentencias::PedidosActivos($_SESSION["id_cliente"]);

                        //Se valida si no existen pedidos activos para ese cliente, en ese caso se creara un nuevo pedido
                        if($datos_pedidos == null)
                        {
                            //variable que tiene los datos del producto seleccionado
                            $dato_producto = Sentencias::Seleccionar("productos", "id", array($_GET["id_producto"]), 0, null);

                            //Variable con las existencia de ese producto
                            $existencias = $dato_producto["inventario"];
                            $nuevas_existencias = $existencias - 1;

                            //Valida que haya al menos una existencia
                            if($existencias > 0)
                            {
                                //Se prepara la sentencia para crear un nuevo pedido
                                $campos_valores = array
                                (
                                    'estado' => 1,
                                    'id_cliente' => $_SESSION["id_cliente"]
                                );

                                Sentencias::Insertar("pedidos", $campos_valores, 0, null);

                                //variable que tiene el nuevo pedido activo del cliente
                                $dato_pedido = Sentencias::PedidosActivos($_SESSION["id_cliente"]);

                                //Se prepara la sentencia para añadir ese producto al nuevo carrito
                                $campos_valores_pedidos = array
                                (
                                    'cantidad' => 1,
                                    'id_producto' => $_GET["id_producto"],
                                    'id_pedido' => $dato_pedido["id"]
                                );

                                Sentencias::Insertar("detalles_pedidos", $campos_valores_pedidos, 0, null);

                                //se prepara la sentencia para eliminar una existencia del producto
                                $campos_valores_producto = array
                                (
                                    'inventario' => $nuevas_existencias
                                );

                                $condiciones_parametros_producto = array
                                (
                                    'id' => $_GET["id_producto"]
                                );

                                Sentencias::Actualizar("productos", $campos_valores_producto, $condiciones_parametros_producto, 0, null);

                                Ventanas::Mensaje(1, "Perfecto, añadiste este producto a tu carrito", null);
                            }
                            
                            else
                            {
                                header("Location: tienda.php");
                            }
                        }

                        //En caso de que exista, se agregara a ese pedido
                        else
                        {
                            //Aqui se verifica si existe ese producto en el carrito
                            $parametros = array($datos_pedidos["id"], $_GET["id_producto"]);
                            $dato_detalle_pedido = Sentencias::CantidadPedido($parametros);

                            //Aqui se realiza la operacion cuando no existe ese producto en el carrito
                            if($dato_detalle_pedido == null)
                            {
                                //Se prepara la sentencia para añadir ese producto al nuevo carrito
                                $campos_valores_pedidos = array
                                (
                                    'cantidad' => 1,
                                    'id_producto' => $_GET["id_producto"],
                                    'id_pedido' => $datos_pedido["id"]
                                );

                                Sentencias::Insertar("detalles_pedidos", $campos_valores_pedidos, 0, null);

                                //se prepara la sentencia para eliminar una existencia del producto
                                $campos_valores_producto = array
                                (
                                    'inventario' => $nuevas_existencias
                                );

                                $condiciones_parametros_producto = array
                                (
                                    'id' => $_GET["id_producto"]
                                );

                                Sentencias::Actualizar("productos", $campos_valores_producto, $condiciones_parametros_producto, 0, null);

                                Ventanas::Mensaje(1, "Perfecto, añadiste este producto a tu carrito", null);
                            }

                            //Aqui se realiza la operacion cuando existe ese producto en el carrito
                            else
                            {
                                $cantidad = $dato_detalle_pedido["cantidad"] + 1;

                                //se prepara la sentencia para actualizar
                                $campos_valores = array
                                (
                                    'cantidad' => $cantidad
                                );

                                $condiciones_parametros = array
                                (
                                    'id' => $dato_detalle_pedido["id"]
                                );

                                Sentencias::Actualizar("detalles_pedidos", $campos_valores, $condiciones_parametros, 0, null);

                                //se prepara la sentencia para eliminar una existencia del producto
                                $campos_valores_producto = array
                                (
                                    'inventario' => $nuevas_existencias
                                );

                                $condiciones_parametros_producto = array
                                (
                                    'id' => $_GET["id_producto"]
                                );

                                Sentencias::Actualizar("productos", $campos_valores_producto, $condiciones_parametros_producto, 0, null);

                                Ventanas::Mensaje(1, "Perfecto, añadiste este producto a tu carrito", null);
                            }
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "Debes haber iniciado sesion para añadir productos a tu carrito", null);
                    }
                }

                else
                {
                    header("Location: tienda.php");
                }
                
            }
        }

        //Metodo con el render de cada tarjeta de producto
        public static function Tarjeta($row_productos)
        {
            echo
            ("
                <div class='col s10 offset-s1 m4 l2'>
                    <div class='card'>
                        <div class='card-image'>
                            <img src='../img/productos/$row_productos[imagen]'>
                            <!--<span class='card-title'>Card Title</span>-->
                        </div>
                        <div class='card-content'>
                            <p>$row_productos[producto]</p>
                        </div>
                        <div class='card-action'>
                            <a href='productos.php?id_producto=".$row_productos['id']."'>Ver</a>
                            <a href='tienda.php?id_producto=".$row_productos['id']."'><i class='material-icons'>shop</i></a>
                        </div>
                    </div>
                </div>
            ");
        }
    }
?>