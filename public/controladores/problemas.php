<?php
    //clase para recuperar contraseña
    class Recuperar
    {
        //Metodo para la validacion y recuperar contraseña
        public static function Clave()
        {
            //Se valida que se haya presionado el boton para recuperar
            if(isset($_POST["formulario"]))
            {
                //Se valida que el email no este vacio
                if($_POST["email"] != "")
                {
                    //Se valida que cumpla como email
                    if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                    {
                        //variable con los datos del usuario
                        $datos_cliente = Sentencias::Seleccionar("clientes", "email", array($_POST["email"]), 0, null);
                        
                        //Se genera la nueva clave
                        $bytes = random_bytes(5);
                        $publica = bin2hex($bytes);
                        $clave = password_hash(bin2hex($bytes), PASSWORD_DEFAULT);
                        
                        //se prepara la sentencia
                        $campos_valores = array
                        (
                            'clave' => $clave,
                            'estado' => 2,
                            'intentos' => 0,
                            'token' => 0
                        );

                        $condiciones_parametros = array('id' => $datos_cliente["id"]);

                        Sentencias::Actualizar("clientes", $campos_valores, $condiciones_parametros, 0, null);
                        self::Correo($datos_cliente["email"], $datos_cliente["nombres"]." ".$datos_cliente["apellidos"], $publica);
                        //Ventanas::Mensaje(1, "Perfecto, se modificaron tu datos, ve a tu correo para poder verlos", "login.php");

                    }   

                    else
                    {
                        Ventanas::Mensaje(2, "El email no es valido", null);
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "No dejes campos vacios", null);
                }
            }
        }

        //metodo en donde se crea el correo de recuperacion
        public static function Correo($destino, $nombre, $clave)
        {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = "storezeven1@gmail.com";
            $mail->Password = "paraprogra";
            $mail->setFrom('storezeven1@gmail.com', 'ZevenStore');
            $mail->addAddress($destino, $nombre);
            $mail->Subject = 'Generacion de nueva contraseña';
            $mail->msgHTML('Aqui va tu clave', null);
            $mail->AltBody = "Esta es tu nueva clave = $clave";
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message sent!";
            }
        }
    }
?>