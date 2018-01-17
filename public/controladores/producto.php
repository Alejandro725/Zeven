<?php
    //clase para las opciones y render de los productos
    class opcionesProductos
    {
        //metodo para renderizar y agregar productos al carrito
        public static function Producto()
        {
            //Verifica si ha hecho click en el boton para el input
            if(isset($_POST["añadir"]))
            {
                //Valida que el input no sobrepasa lo ya establecido
                if(Validaciones::Cantidad($_POST["cantidad"], 1, 10, 2))
                {
                    //Valida que haya iniciado sesion
                    if(!empty($_SESSION["id_cliente"]))
                    {
                        //Variable que tendra el carrito en caso de que exista
                        $dato_pedido = Sentencias::PedidosActivos($_SESSION["id"]);
                        
                        //Variable que tiene el producto seleccionado
                        $dato_producto = Sentencias::Seleccionar("productos", "id", array($_GET["id_producto"]), 0, null);

                        //Se valida si hay mas inventario que la cantidad a pedir
                        if($dato_producto["inventario"] > $_POST["cantidad"])
                        {
                            //Verifica si hay un carrito activo
                            if($dato_pedido != null)
                            {
                                $parametros = array($datos_pedidos["id"], $_GET["id_producto"]);

                                $dato_pedido = sentenciasDetallesPedidos::CantidadPedido($parametros);

                                //Aqui realiza la operacion cuando no existe ese producto en el carrito
                                if($dato_pedido == null)
                                {
                                    $campos_valores_pedido = array
                                    (
                                        'estado' => 1,
                                        'id_cliente' => $_SESSION["id_cliente"]
                                    );

                                    Sentencias::Insertar("pedidos", $campos_valores_pedido, 0, null);

                                    //Variable que tendra el carrito en caso de que exista
                                    $nuevo_dato_pedido = Sentencias::PedidosActivos($_SESSION["id"]);

                                    //Se prepara la sentencia para agregar ese producto a un detalle
                                    $campos_valores_detalle = array
                                    (
                                        'cantidad' => $_POST["cantidad"],
                                        'id_producto' => $_GET["id_producto"],
                                        'id_pedido' => $nuevo_dato_pedido["id"]
                                    );

                                    Sentencias::Insertar("detalles_pedidos", $campos_valores_detalle, 0, null);

                                    //Se prepara la sentencia para actualizar las existencias de ese producto
                                    $nuevo_inventario = $dato_producto["Inventario"] - $_POST["cantidad"];

                                    $campos_valores_producto = array
                                    (
                                        'cantidad' => $nuevo_inventario
                                    );

                                    $condiciones_parametros = array
                                    (
                                        'id_producto' => $_GET["id_producto"]
                                    );

                                    Sentencias::Actualizar("productos", $campos_valores_producto, $condiciones_parametros, 0, null);

                                    Ventanas::Mensaje(1, "Perfecto, añadiste este producto a tu carrito", "productos.php?id_producto=$_GET[id_producto]");
                                }

                                //Aqui se realiza la operacion cuando ese producto existe en el carrito
                                else
                                {
                                    $cantidad = $dato_pedido["cantidad"] + $_POST["cantidad"];

                                    $campos_valores_detalle = array('cantidad' => $cantidad);
                                    $condiciones_parametros = array('id_producto' => $_GET["id_producto"]);
                                    
                                    Sentencias::Actualizar("detalles_pedidos", $campos_valores_detalle, $condiciones_parametros, 0, null);

                                    //Se prepara la sentencia para actualizar las existencias de ese producto
                                    $nuevo_inventario = $dato_producto["Inventario"] - $_POST["cantidad"];

                                    $campos_valores_producto = array
                                    (
                                        'cantidad' => $nuevo_inventario
                                    );

                                    $condiciones_parametros_producto = array
                                    (
                                        'id_producto' => $_GET["id_producto"]
                                    );

                                    Sentencias::Actualizar("productos", $campos_valores_producto, $condiciones_parametros_producto, 0, null);

                                    Ventanas::Mensaje(1, "Perfecto, añadiste este producto a tu carrito", "productos.php?id_producto=$_GET[id_producto]");
                                }
                            }

                            else
                            {

                            }
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "No hay suficientes existencias de este producto, reduce la cantidad", null);
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "Debes iniciar sesion para agregar este producto a tu carrito", null);
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "La cantidad no es valida", null);
                }
            }

            //Verifica que se ha hecho click en el boton para los comentarios
            if(isset($_POST["formulario"]))
            {
                //Valida que el formulario de comentarios no este vacio
                if($_POST["comentario"] != "")
                {
                    if(Validaciones::longitud($_POST["comentario"], 255))
                    {
                        $hoy = getdate();
                        $fecha = $hoy["year"]."-".$hoy["mon"]."-".$hoy["mday"];

                        $campos_valores = array
                        (
                            'comentario' => $_POST["comentario"],
                            'fecha' => $fecha,
                            'estado' => 1,
                            'id_producto' => $_GET["id_producto"],
                            'id_cliente' => $_SESSION["id_cliente"]
                        );

                        Sentencias::Insertar("comentarios", $campos_valores, 0, null);
                        Ventanas::Mensaje(1, "Perfecto, agregaste un comentario", "productos.php?id_producto=$_GET[id_producto]");
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "El comentario no es valido", null);
                    }
                }
            }

            //Verifica si se ha ingresado al sitio mediante el get
            if(!empty($_GET["id_producto"]))
            {
                //Valida que sea un numero el que se ha recibio como parametro
                if(is_numeric($_GET["id_producto"]))
                {
                    //Variable que tiene los datos del producto seleccionado
                    $datos_producto = Sentencias::Seleccionar("productos", "id", array($_GET["id_producto"]), 0, null);

                    //Verifica si ese producto exsiste
                    if($datos_producto != null)
                    {
                        //Variable para el inventario del producto
                        $estado;

                        if($datos_producto["inventario"] > 0)
                        {
                            $estado = "Disponible";
                        }

                        else
                        {
                            $estado = "No disponible";
                        }

                        //Empieza a renderizar el producto
                        echo
                        ("
                            <!--Aqui se empieza a  incluir el  producto-->
                            <div class='row mar3'>
                                
                                <!--Aqui se colocaran las imagenes, datos y comentarios del  producto-->         
                                <div class='col s10 offset-s1 l6 offset-l1'>

                                    <!--Aqui se agregan las imagenes del  producto-->
                                    <div  class='col s12 m6'>

                                        <!--Se colaca la imagen principal-->
                                        <img class='img-principal' src='../img/productos/$datos_producto[imagen]'>
                                        <!--Aqui termina la imagen principal-->
                        ");

                        //Variable que contiene las imagenes pertenecientes a ese producto
                        $datos_imagenes_producto = Sentencias::Seleccionar("imagenes_productos", "id_producto", array($_GET["id_producto"]), 1, null);

                        //aqui es donde se renderizan todas las imagenes
                        foreach($datos_imagenes_producto as $row_imagenes_productos)
                        {
                            echo
                            ("
                                <div class='center-align'>
                                    <img class='img-secundaria' src='../img/productos/$row_imagenes_productos[imagen]'>
                                </div>
                            ");
                        }

                        //Continua renderizando el producto, aqui se agregan las especificaciones
                        echo
                        ("                                
                                    </div>
                                    <!--Aqui se terminan las imagenes del  producto-->

                                    <!--Aqui se colocara el nombre, precio y estado-->
                                    <div class='col s12 m6'>
                                        
                                        <!--Se agrega el nombre del producto-->
                                        <h4>".$datos_producto["producto"]."</h4>
                                        <!--Aqui termina el nombre del producto-->

                                        <!--Aqui se agrega el precio del  producto-->
                                        <h4>$".$datos_producto["precio"]."</h4>
                                        <!--Aqui se  termina el precio del producto-->

                                        <!--Aqui se agrega el estado d el producto-->
                                        <h4>".$estado."</h4>
                                        <!--Aqui se termina el estado del producto-->

                                        <form method='post'>
                                            <div class='col s3'>
                                                <input id='cantidad' name='cantidad' class='validate' type='number' min='1' max='10' step='1' value='1'>
                                            </div>    
                                            <button name='añadir' class='waves-effect waves-light btn blue darken-4'>Agregar al carrito</button>                             
                                        </form>

                                    </div>
                                    <!--Aqui terminan datos del producto-->

                                    <!--Aqui se agrega la seccion de especificaciones y comentarios-->
                                    <div class='col s12'>
                                        <ul class='tabs'>
                                            <li class='tab col s6'><a class='active blue-text text-darken-1' href='#especs'>Especificaciones</a></li>
                                            <li class='tab col s5'><a class='blue-text text-darken-1' href='#comentarios'>Comentarios</a></li>
                                        </ul>
                                    </div>

                                    <!--Aqui se colocan las especificaciones-->
                                    <div class='col s12' id='especs'>
                                        <p>Procesador: ".$datos_producto["procesador"]."</p>
                                        <p>Ram: ".$datos_producto["ram"]."</p>
                                        <p>Pantalla: ".$datos_producto["pantalla"]." pulgadas</p>
                                        <p>Tipo de Pantalla: ".$datos_producto["tipo_pantalla"]."</p>
                                        <p>Peso: ".$datos_producto["peso"]." gramos</p>
                                        <p>Dimensiones:  ".$datos_producto["dimensiones"]."</p>
                                        <p>Almacenamiento: ".$datos_producto["memoria"]." GB</p>
                                        <p>Ampliable: ".$datos_producto["ampliable"]." GB</p>
                                        <p>Bateria: ".$datos_producto["bateria"]." Mah</p>
                                        <p>Camara: ".$datos_producto["camara"]." Mp</p>
                                        <p>Camara Delantera: ".$datos_producto["frontal"]." Mp</p>                            
                                        <p>Sistema: ".$datos_producto["sistema"]."</p>
                                    </div>
                                    <!--Aqui se terminan las especeficaciones-->
                                <div id='comentarios'>
                        ");

                        //Aqui se renderizan los comentarios dependiendo si ya inicio o no sesion
                        if(!empty($_SESSION["id_cliente"]))
                        {
                            echo
                            ("
                                <!--Aqui se colocan los comentarios-->
                                <form method='post' class='col s12 center-align' id='comentarios'>
                                    <div class='input-field col s12'>
                                        <input id='comentario' name='comentario' type='text' class='validate' data-length='255'>
                                        <label for='comentario'>Comentario</label>
                                    </div>
                                    <br>
                                    <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Enviar</button>
                                </form>
                                <!--Aqui se terminan los comentarios-->

                                <!--Aqui se termina la seccion de especificaciones y comentarios-->
                            ");
                        }

                        else
                        {
                            echo
                            ("
                                <h4>Debes haber iniciado sesion para comentar</h4>
                            ");
                        }

                        echo
                        ("
                            </div>
                        ");

                        //Variable que tiene todos los comentarios pertenencientes a ese producto
                        $datos_comentarios = Sentencias::Seleccionar("comentarios", "id_producto", array($_GET["id_producto"]), 1, null);

                        //Verifica que existan comentarios para ese producto
                        if($datos_comentarios != null)
                        {
                            //Se renderizan todos los comentarios de ese producto
                            foreach($datos_comentarios as $row_comentarios)
                            {
                                //Renderiza los comentarios solo si estan activos
                                if($row_comentarios["estado"] == 1)
                                {
                                    //Variable que contiene los datos del cliente que ingreso el comentario
                                    $dato_cliente = Sentencias::Seleccionar("clientes", "id", array($row_comentarios["id_cliente"]), 0, null);

                                    echo
                                    ("
                                        <div class='col s12 mar3'>
                                            <div class='col s2'>
                                                <img src='../img/clientes/$data_cliente[foto]' width='50' height='50' class='circle responsive-img'>
                                            </div>
                                                
                                            <div class='col s8'>
                                                <label>".$row_comentarios['comentario']."</label>
                                            </div>
                                        </div>
                                    ");
                                }
                            }
                        }

                        else
                        {
                            echo
                            ("
                                <div>
                                    <h4>No hay comentarios para este producto, se el primero</h4>
                                </div>
                            ");
                        }

                        echo
                        ("
                                </div>
                            </div>
                        ");
                    }

                    else
                    {
                        header("Location: tienda.php");
                    }
                }

                else
                {
                    header("Location: tienda.php");
                }
            }

            else
            {
                header("Location: tienda.php");
            }
        }
    }
?>