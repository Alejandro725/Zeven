<?php
    /*Clase para las validaciones*/
    class Validaciones
    {
        /*Metodo para validar imagenes*/
        public static function imagen($imagen)
        {
            if($imagen["size"] <= 2097152)
            {
                if($imagen["type"] == "image/jpeg")
                {
                    return true;
                }

                else
                {
                    return false;
                }
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar el nombre de usuario*/
        public static function email($email)
        {
            if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
            {
                return true;
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar un numero de telefono*/
        public static function nombre($string)
        {
            if(preg_match("/([a-zA-Z])/", $string))
            {
                return true;
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar la longitud de un string*/
        public static function longitud($string, $longitud)
        {
            if(strlen($string) <= $longitud)
            {
                return true;
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar un numero*/
        public static function numero($numero)
        {
            if(strlen($numero) == 8)
            {
                if(preg_match("/([0-9])/", $numero))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar que solo hayan numetros*/
        public static function solo_numero($numero)
        {
            if(preg_match("/([0-9])/", $numero))
            {
                return true;
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar el dui sin guion*/
        public static function dui($dui)
        {
            if(strlen($dui) == 9)
            {
                if(preg_match("/([0-9])/", $dui))
                {
                    return true;
                }

                else
                {
                    return false;
                }
            }

            else
            {
                return false;
            }
        }

        /*Metodo para validar caracteres alfa numericos*/
        public static function alfanumerico($string)
        {
            if(preg_match("/([a-zA-Z0-9])/", $string))
            {
                return true;
            }

            else
            {
                return false;
            }
        }

        //Validar la cantidad de un input
        public static function Cantidad($numero, $minimo, $maximo, $cantidad)
        {
            if($numero >= $minimo && $numero <= $maximo)
            {
                if(strlen($numero) <= $cantidad)
                {
                    if(preg_match("/([1-9])/", $numero))
                    {
                        return true;
                    }

                    else
                    {
                        return false;
                    }
                }

                else
                {
                    return false;
                }
            }
            
            else
            {
                return false;
            }
        }
    }
?>  