<?php
    //clase con las opciones y los renders del carrito
    class opcionesCarrito
    {
        //metodo para las opciones y renders del carrito
        public static function Carrito()
        {
            //Valida que el cliente haya inciado sesion
            if($_SESSION["id_cliente"])
            {
                //Aqui se obtiene el carrito activo del usuario
                $data_pedido = sentenciasPedidos::PedidosActivos($_SESSION["id"]);

                //Valida que tenga un carrito 
                if($data_pedido != null)
                {
                    //variable que tiene todos los detalles pedidos de ese carrito
                    $datos_detalles_pedidos = Sentencias::Seleccionar("detalles_pedidos", "id", array($data_pedido["id"]), 1, null);

                    //Variable con el total final
                    $total_final = 0;

                    //Se renderiza el esquema de la tabla
                    echo
                    ("
                        <div class='row'>
                            <table class='highlight centered col s10 offset-s1 l8 offset-l1'>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                    ");

                    //Valida que existan productos en ese carrito
                    if($datos_detalles_pedidos != null)
                    {
                        //Renderiza los datos en la tabla
                        foreach($datos_detalles_pedidos as $row_detalles_productos)
                        {
                            //Variable que tiene los datos de un producto en especifico del carrito
                            $dato_producto = Sentencias::Seleccionar("productos", "id", array($row_detalles_productos["id_producto"]), 0, null);
                            $total = $dato_producto["precio"] * $row_detalles_productos["cantidad"];

                            echo
                            ("
                                <tr>
                                    <td>".$dato_producto["producto"]."</td>
                                    <td>".$row_detalles_productos["cantidad"]."</td>
                                    <td>$".$dato_producto["precio"]."</td>
                                    <td>$".$total."</td>
                                    <td>
                                        <a href='carrito.php?id_detalle=".$row_detalles_productos['id']."' class='red-text text-darken-a'><i class='material-icons'>delete</i></a>
                                    </td>
                                </tr>
                            ");

                            $total_final = $total_final + $total;
                        }   
                        
                         echo
                        ("      
                                    </tbody>
                                </table>

                                <div class='col s10 offset-s1 l3 center-align'>
                                    <h3>Total: $".$total_final."</h3>
                                    <a href='carrito.php?accion=1' class='waves-effect waves-light btn blue darken-4'>Relizar Pedido</a>
                                </div>
                            </div>
                        ");
                    }

                    //Aqui se hacen las operaciones para eliminar ese producto del carrito y ademas de realizar el pedidos
                    //Aqui se hacen las operaciones para realizar el pedido
                    if(!empty($_GET["accion"]))
                    {
                        //Se valida que el parametro sea 1
                        if($_GET["accion"] == 1)
                        {
                            if($datos_detalles_pedidos != null)
                            {
                                $hoy = getdate();
                                $fecha = $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"]; 

                                $campos_valores_pedidos = array
                                (
                                    'fecha_pedido' => $fecha,
                                    'total' => $total_final,
                                    'estado' => 2
                                );

                                $condiciones_parametros_pedidos = array
                                (
                                    'id' => $data_pedido["id"]
                                );

                                Sentencias::Actualizar("pedidos", $campos_valores_pedidos, $condiciones_parametros_pedidos, 0, null);

                                Ventanas::Mensaje(1, "Perfecto, compra realizada", "cuenta.php");
                            }

                            else
                            {
                                Ventanas::Mensaje(2, "No tienes ningun producto en tu carrito", "tienda.php");
                            }
                        }

                        else
                        {
                            header("Location: carrito.php");
                        }
                    }

                    //Metodo para eliminar un producto del carrito
                    if(!empty($_GET["id_detalle"]))
                    {
                        //Se valida que el parametro sea de tipo numerico
                        if(is_numeric($_GET["id_pedido"]))
                        {
                            //Variable que tendra los datos de ese pedido
                            $dato_detalle = Sentencias::Seleccionar("detalles_pedidos", "id", array($_GET["id_detalle"]), 0, null);

                            //Variable que tendra el producto de ese detalle
                            $data_producto = Sentencias::Seleccionar("productos", "id", array($dato_detalle["id_producto"]), 0, null);

                            $nuevo_inventario = $dato_detalle["cantidad"] + $data_producto["inventario"];

                            $condiciones_parametros_detalle = array('id' => $_GET["id_detalle"]);

                            Sentencias::Eliminar("detalles_pedidos", $condiciones_parametros_detalle, 0, null);

                            //se prepara la sentencia para actualizar el inventario de ese producto
                            $campos_valores_producto = array('inventario' => $nuevo_inventario);
                            $condiciones_parametros_producto = array('id' => $dato_detalle["id_producto"]);

                            Sentencias::Actualizar("producto", $campos_valores_producto, $condiciones_parametros_producto, 0, null);

                            Ventanas::Mensaje(3, "Has eliminado este producto de tu carrito", null);            
                        }

                        else
                        {
                            header("Location: tienda.php");
                        }
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "No tienes ningun carrito activo", "tienda.php");
                }
            }

            else
            {
                Ventanas::Mensaje(2, "Debes iniciar sesion antes de ver tu carrito", "login.php");
            }
        }
    }
?>