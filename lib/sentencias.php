<?php
    //Clase con las diferentes sentencias para la base
    class Sentencias
    {
        //Metodo para Seleccionar
        public static function Seleccionar($tabla, $campo, $parametro, $cantidad, $like)
        {
            if($campo != null)
            {
                if($like != null)
                {
                    $sql = "SELECT * FROM $tabla WHERE $campo LIKE ? ORDER BY id";
                }

                else
                {
                    $sql = "SELECT * FROM $tabla WHERE $campo = ?";
                }               

                if($cantidad == 0)
                {
                    $data = Database::getRow($sql, $parametro);

                    return $data;
                }

                else if($cantidad > 0) 
                {
                    $data = Database::getRows($sql, $parametro);

                    return $data;
                }
            }

            else
            {
                $sql = "SELECT * FROM $tabla";

                $data = Database::getRows($sql, null);

                return $data;
            }
        }

        //Metodo para Seleccionar 2.0
        public static function Buscar($tabla, $campo, $parametro, $cantidad, $like, $orden)
        {
            if($campo != null)
            {
                if($like != null)
                {
                    if($orden != null)
                    {
                        if($orden == 1)
                        {
                            $sql = "SELECT * FROM $tabla WHERE $campo LIKE ? ORDER BY id ASC";
                        }

                        if($orden == 0)
                        {
                            $sql = "SELECT * FROM $tabla WHERE $campo LIKE ? ORDER BY id DESC";
                        }
                    }

                    else
                    {
                        $sql = "SELECT * FROM $tabla WHERE $campo LIKE ? ORDER BY id";
                    }                  
                }

                else
                {
                    if($orden != null)
                    {
                        if($orden == 1)
                        {

                        }

                        if($orden == 0)
                        {

                        }
                    }

                    else
                    {
                        $sql = "SELECT * FROM $tabla WHERE $campo = ?";
                    }       
                }               

                if($cantidad == 0)
                {
                    $data = Database::getRow($sql, $parametro);

                    return $data;
                }

                else if($cantidad > 0) 
                {
                    $data = Database::getRows($sql, $parametro);

                    return $data;
                }
            }

            else
            {
                if($orden != null)
                {
                    if($orden == 1)
                    {
                        $sql = "SELECT * FROM $tabla ASC";
                    }

                    if($orden == 0)
                    {
                        $sql = "SELECT * FROM $tabla DESC";
                    }
                }

                else
                {
                    $sql = "SELECT * FROM $tabla";
                }       

                $data = Database::getRows($sql, null);

                return $data;
            }
        }

        //Metodo para los pedidos activos
        public static function PedidosActivos($datos)
        {
            $sql = "SELECT * FROM pedidos WHERE id_cliente = ? AND estado = 1";

            $params = array($datos);

            $data = Database::getRow($sql, $params);
            
            return $data;
        }

        //Metodo para verificar si ya existe ese producto en el carrito.
        public static function CantidadPedido($datos)
        {
            $sql ="SELECT * FROM detalles_pedidos WHERE id_pedido = ? AND id_producto = ?";

            $data = Database::getRow($sql, $datos);
        
            return $data;
        }

        //Metodo count
        public static function Count($tabla)
        {
            $sql = "SELECT COUNT(*) FROM $tabla";

            $data = Database::getRow($sql, null);

            return $data;
        }

        //Metodo para Insertar datos
        public static function Insertar($tabla, $campos_valores, $mensaje, $pagina)
        {
            try
            {
                //Se divide el array de campos_valores
                $campos = array_keys($campos_valores);
                $valores = array_values($campos_valores);
                $cantidad = array();

                foreach($valores as $valor)
                {
                    $cantidad[] = "?";
                }

                $sql = "INSERT INTO $tabla(";
                $sql .= join(", ", $campos);
                $sql .= ") VALUES (";
                $sql .= join(", ", $cantidad);
                $sql .= ")";

                //echo $sql;

                if(Database::executeRow($sql, $valores))
                {
                    if($mensaje != 0)
                    {
                        Ventanas::Mensaje(1, "La operacion se ha realizado con exito", "$pagina");
                    }       
                }  

                else
                {
                    throw new Exception(Database::$error[1]);
                }
            }

            catch(Exception $error)
            {
                Ventanas::Mensaje(2, $error->getMessage(), null);
            }
        }

        //Metodo para actualizar
        public static function Actualizar($tabla, $campos_valores, $condiciones_parametros, $mensaje, $pagina)
        {
            try
            {
                //Se divide la variable campos_valores
                $campos = array_keys($campos_valores);
                $valores = array_values($campos_valores);

                //Se divide la variable condiciones_parametros
                $condiciones = array_keys($condiciones_parametros);
                $parametro = array_values($condiciones_parametros);

                $sql = "UPDATE $tabla SET ";
                $sql .= join(" = ?, ", $campos);
                $sql .= " = ?";
                $sql .= " WHERE ";   
                $sql .= join(" = ?, ", $condiciones);
                $sql .= " = ?";

                $datos = array_merge($valores, $parametro);

                //echo $sql;
                //print_r($datos);

                if(Database::executeRow($sql, $datos))
                {
                    if($mensaje != 0)
                    {
                        Ventanas::Mensaje(1, "La operacion se ha realizado con exito", $pagina);
                    }               
                }  

                else
                {
                    throw new Exception(Database::$error[1]);
                }
            }

            catch(Exception $error)
            {
                Ventanas::Mensaje(2, $error->getMessage(), null);
            }
        }

        //Metodo para eliminar
        public static function Eliminar($tabla, $condiciones_parametros, $mensaje, $pagina)
        {
            try
            {
                //Se divide la variable condiciones_parametros
                $condiciones = array_keys($condiciones_parametros);
                $parametro = array_values($condiciones_parametros);

                $sql = "DELETE FROM $tabla";
                $sql .= " WHERE ";   
                $sql .= join(" = ?, ", $condiciones);
                $sql .= " = ?";

                echo $sql;
                print_r($datos);

                /*if(Database::executeRow($sql, $parametro))
                {
                    if($mensaje != 0)
                    {
                        Ventanas::Mensaje(1, "La operacion se ha realizado con exito", $pagina);
                    }               
                }  

                else
                {
                    throw new Exception(Database::$error[1]);
                }*/
            }

            catch(Exception $error)
            {
                Ventanas::Mensaje(2, $error->getMessage(), null);
            }
        }

        //Metodo para paginacion
        public static function Paginacion($tabla, $posicion, $cantidad)
        {
            $sql = "SELECT * FROM $tabla LIMIT $posicion, $cantidad";

            $data = Database::getRows($sql, null);

            return $data;
        }

        //Metodo para busqueda de productos ingresados en ese mes
        public static function BusquedaPM($tabla, $inicio, $fin)
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT producto, marca, fecha FROM productos P, marcas M WHERE fecha BETWEEN $inicio AND $fin AND P.id_marca = M.id";

            $data = Database::getRows($sql, null);
            
            return $data;
        }

        //Metodo para busqueda de ventas en ese mes
        public static function VentasPM($inicio, $fin)
        {
            $sql = "SELECT producto, SUM(cantidad) AS total, SUM(cantidad)*precio as ventas FROM pedidos P, detalles_pedidos D, productos R 
                    WHERE D.id_producto = R.id AND D.id_pedido = P.id 
                    AND P.fecha_pedido BETWEEN $inicio AND $fin GROUP BY R.id ";

            $data = Database::getRows($sql, null);

            return $data;
        }

        //Metodo para busqueda de comentarios por producto
        public static function ComentariosP($id)
        {
            $sql = "SELECT usuario, comentario, O.fecha, producto FROM clientes C, comentarios O, productos P 
            WHERE O.id_producto = $id AND O.id_producto = P.id AND O.id_cliente = C.id ORDER BY P.id ";

            $data = Database::getRows($sql, null);

            return $data;
        }

        //Metodo para obtener los clientes de un mes
        public static function ClientesM($inicio, $fin)
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT usuario, email, fecha FROM clientes WHERE fecha BETWEEN $inicio AND $fin";

            $data = Database::getRows($sql, null);
            
            return $data;
        }

        //Metodo para generar grafica de productos mas vendidos
        public static function VendidosG()
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT producto, SUM(cantidad) AS total, SUM(cantidad)*precio as ventas FROM pedidos P, detalles_pedidos D, productos R 
            WHERE D.id_producto = R.id AND D.id_pedido = P.id AND P.fecha_pedido 
            GROUP BY R.id ORDER BY total DESC";

            $data = Database::getRows($sql, null);
            
            return $data;
        }

        //Metodo para generar grafico de los productos mas comentados
        public static function ComentadosG()
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT COUNT(comentario)as numero, producto FROM comentarios C, productos P
             WHERE C.id_producto = P.id 
             GROUP BY P.id ORDER BY numero DESC ";

            $data = Database::getRows($sql, null);
            
            return $data;
        }

        //Metodo para generar grafico de las ventas por mes
        public static function VentasMA($inicio, $fin)
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT SUM(cantidad)*precio as ventas FROM pedidos P, detalles_pedidos D, productos R 
            WHERE D.id_producto = R.id AND D.id_pedido = P.id AND P.fecha_pedido BETWEEN $inicio AND $fin ";

            $data = Database::getRow($sql, null);
            
            return $data;
        }

        //Metodo para generar grafica con los clientes que mas comentan
        public static function ComentariosC()
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT usuario, COUNT(comentario) as total FROM clientes C, comentarios D 
                    WHERE D.id_cliente = C.id 
                    GROUP BY C.id ORDER BY total DESC ";

            $data = Database::getRows($sql, null);
            
            return $data;
        }

        //Metodo para generar el grafico con los clientes que mas compran
        public static function ComprasC()
        {
            //$sql = "SELECT * FROM $tabla WHERE $fecha BETWEEN $inicio AND $fin"; 
            $sql = "SELECT usuario, SUM(cantidad) as total FROM clientes C, pedidos P, detalles_pedidos D 
                    WHERE D.id_pedido = P.id AND P.id_cliente = C.id 
                    GROUP BY C.id ORDER BY total DESC ";

            $data = Database::getRows($sql, null);
            
            return $data;
        }
    }
?>