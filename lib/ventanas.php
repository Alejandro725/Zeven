<?php
    /*Clase para mostrar ventanas al realizar operaciones*/
    class Ventanas
    {
        /*Metodo para mostrarlas*/
        public static function Mensaje($type, $message, $url)
        {
            if(is_numeric($message))
            {
                switch($message)
                {
                    case 1045:
                        $text = "Autenticación desconocida";
                        break;
                    case 1049:
                        $text = "Base de datos desconocida";
                        break;
                    case 1054:
                        $text = "Nombre de campo desconocido";
                        break;
                    case 1062:
                        $text = "Dato duplicado, no se puede guardar";
                        break;
                    case 1146:
                        $text = "Nombre de tabla desconocido";
                        break;
                    case 1451:
                        $text = "Registro ocupado, no se puede eliminar";
                        break;
                    case 2002:
                        $text = "Servidor desconocido";
                        break;
                    default:
                        $text = $message;
                }
            }
            else
            {
                $text = $message;
            }

            //$text = addslashes($message);
            switch($type)
            {
                case 1:
                    $title = "Éxito";
                    $icon = "success";
                    break;
                case 2:
                    $title = "Error";
                    $icon = "error";
                    break;
                case 3:
                    $title = "Advertencia";
                    $icon = "warning";
                    break;
                case 4:
                    $title = "Aviso";
                    $icon = "info";
            }
            if($url != null)
            {
                print("<script>swal({title: '$title', text: '$text', type: '$icon', confirmButtonText: 'Aceptar', allowOutsideClick: false, allowEscapeKey: false}).then(function(){location.href = '$url'})</script>");
            }
            else
            {
                print("<script>swal({title: '$title', text: '$text', type: '$icon', confirmButtonText: 'Aceptar', allowOutsideClick: false, allowEscapeKey: false})</script>");
            }
        }
    }
?>