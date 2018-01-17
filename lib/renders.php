<?php 
    //clase para renderizar psecciones ya definidas
    class Render
    {
        //metodo para colocar los archivos necesarios
        public static function Header($titulo)
        {
            echo
            ("
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Zeven Store|$titulo</title>
                        <!--Import Google Icon Font-->
                        <link href='../css/iconos.css' rel='stylesheet'>
                        <!--Import materialize.css-->
                        <link type='text/css' rel='stylesheet' href='../css/materialize.min.css'  media='screen,projection'/>
                        <link href='css/estilos.css' rel='stylesheet'>  
                        <link href='../css/sweetalert2.min.css' rel='stylesheet'>
                        <script type='text/javascript' src='../js/sweetalert2.min.js'></script>

                        <!--Let browser know website is optimized for mobile-->
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
                    </head>

                    <body>
            ");
        }

        //Metodo del navbar
        public static function Navbar($objetos)
        {
            //Se crean cinco variables las cuales cambiaran dependiendo de la pagina en la que se encuentren
            $tienda = null;
            $nosotros = null;
            $informacion = null;
            $carrito = null;
            $cuenta = null;

            //Se valida la pagina en la que se esta
            if($_SERVER["PHP_SELF"] == '/Zeven/public/tienda.php' || $_SERVER["PHP_SELF"] == '/Zeven/public/producto.php')
            {
                $tienda = 'active';
            }

            if($_SERVER["PHP_SELF"] == '/Zeven/public/nosotros.php')
            {
                $nosotros = 'active';
            }

            if($_SERVER["PHP_SELF"] == '/Zeven/public/informacion.php')
            {
                $informacion = 'active';
            }

            if($_SERVER["PHP_SELF"] == '/Zeven/public/carrito.php')
            {
                $carrito = 'active';
            }

            if($_SERVER["PHP_SELF"] == '/Zeven/public/login.php' || $_SERVER["PHP_SELF"] == '/Zeven/public/registro.php' || $_SERVER["PHP_SELF"] == '/Zeven/public/problemas.php')
            {
                $cuenta = 'active';
            }

            echo
            ("
                <!--Se crea el navbar-->
                <div class='navbar-fixed'>
                    <nav>
                        <div class='nav-wrapper blue darken-4'>
                            <a href='#!' class='brand-logo center'>Zeven Store</a>
                            <a href='#' data-activates='mobile-demo' class='button-collapse'><i class='material-icons'>menu</i></a>
                            <ul id='nav-mobile' class='left hide-on-med-and-down'>
                                <li class='$tienda'><a href='tienda.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Tienda'><i class='material-icons'>store</i></a></li>
                                <li class='$nosotros'><a href='nosotros.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Nosotros'><i class='material-icons'>supervisor_account</i></a></li>
                                <li class='$informacion'><a href='informacion.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Informaciòn'><i class='material-icons'>info</i></a></li>
                            </ul>
                            <ul id='nav-mobile' class='right hide-on-med-and-down'>
                                <li class='$carrito'><span class='badge blue-darken-4 white-text'>$objetos</span><a href='carrito.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Tu carrito'><i class='material-icons'>shopping_cart</i></a></li>
                                <li class='$cuenta'><a href='login.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Tu cuenta'><i class='material-icons'>person_pin</i></a></li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <ul class='side-nav' id='mobile-demo'>
                    <li class='$tienda'><a href='tienda.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Tienda'><i class='material-icons blue-text text-darken-4'>store</i></a></li>
                    <li class='$nosotros'><a href='nosotros.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Nosotros'><i class='material-icons blue-text text-darken-4'>supervisor_account</i></a></li>
                    <li class='$informacion'><a href='informacion.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Informaciòn'><i class='material-icons blue-text text-darken-4'>info</i></a></li>
                    <li class='$carrito'><a href='carrito.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Tu carrito'><i class='material-icons blue-text text-darken-4'>shopping_cart</i></a></li>
                    <li class='$cuenta'><a href='login.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Tu cuenta'><i class='material-icons blue-text text-darken-4'>person_pin</i></a></li>
                </ul>
            ");
        }

        //Metodo para importar los ultimos componentes
        public static function Body()
        {
            echo
            ("
                        <script type='text/javascript' src='../js/jquery.js'></script>
                        <script type='text/javascript' src='../js/materialize.min.js'></script>
                        <script type='text/javascript' src='js/main.js'></script>
                    </body>
                </html>
            ");
        }

        //Metodo para el navbar del dashboard
        public static function Menu()
        {
            $datos_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            //aqui se renderiza el navbar
            echo
            ("
                <ul id='slide-out' class='side-nav fixed'>
                    <li>
                        <div class='userView'>
                        <div class='background'>
                            <img src='../img/parallax/001.jpg'>
                        </div>
                            <a href='logout.php'><img class='circle' src='../img/usuarios/$_SESSION[foto]'></a>
                            <a href='#!name'><span class='white-text name'>$_SESSION[nombre]</span></a>
                        </div>
                    </li>                    
                
            ");

            if($datos_tipo["usuarios"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='tipos_usuarios.php' id=''>Tipos de usuario</a></li>
                    <li><a class='waves-effect' href='usuarios.php' id=''>Usuarios</a></li>
                ");
            }
            
            if($datos_tipo["marcas"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='marcas.php' id=''>Marcas</a></li>
                ");
            }

            if($datos_tipo["productos"] > 0)
            {
                echo
                ("              
                    <li><a class='waves-effect' href='productos.php'>Productos</a></li>
                    <li><a class='waves-effect' href='relacionar.php'>Imagenes de productos</a></li>
                ");
            }

            if($datos_tipo["noticias"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='noticias.php' id=''>Noticias</a></li>
                ");
            }

            if($datos_tipo["videos"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='videos.php' id=''>Videos</a></li>
                ");
            }

            if($datos_tipo["clientes"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='clientes.php'>Clientes</a></li>
                ");
            }

            if($datos_tipo["comentarios"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='comentarios.php'>Comentarios</a></li>
                ");
            }

            if($datos_tipo["pedidos"] > 0)
            {
                echo
                ("
                    <li><a class='waves-effect' href='pedidos.php'>Pedidos</a></li>
                ");
            }

            echo
            ("
                </ul>
            ");
        }

        public static function Permisos($permiso)
        {
            $parametro = array($_SESSION["tipo"]);

            $datos_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", $parametro, 0, null);

            if($datos_tipo[$permiso] > 0)
            {

            }

            else
            {
                Ventanas::Mensaje(2, "No tienes permiso para esta pagina", "index.php");
            }
        }

        //Funcion para generar los select de cada permiso
        public static function ComboPermisos($nombre)
        {
            echo
            ("
                <div class='input-field col s6 blue-text text-darken-4'>
                    <select name='$nombre'>
                        <option value='0' selected>Nulo</option>
                        <option value='1'>Solo lectura</option>
                        <option value='2'>Solo crear</option>
                        <option value='3'>Solo modificar</option>
                        <option value='4'>Solo eliminar</option>
                        <option value='5'>Crear y modificar</option>
                        <option value='6'>Crear y eliminar</option>
                        <option value='7'>Modificar y eliminar</option>
                        <option value='9'>Todos los permisos</option>
                    </select>
                    <label>Permisos para $nombre</label>
                </div>
            ");
        }

        //Metodo para el menu del cliente
        public static function menuCliente()
        {
            echo
            ("
                <div class='navbar-fixed'>
                    <nav>
                        <div class='nav-wrapper blue darken-4'>
                            <a href='index.php' class='brand-logo center'>Zeven Store</a>
                            <a href='#' data-activates='mobile-demo' class='button-collapse'><i class='material-icons'>menu</i></a>
                            <ul id='nav-mobile' class='right hide-on-med-and-down'>
                                <li class='carrito'><a href='usuario.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Tus datos'><i class='material-icons'>person_pin</i></a></li>
                                <li class='carrito'><a href='compras.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Tus compras'><i class='material-icons'>shopping_cart</i></a></li>
                                <li class='cuenta'><a href='logout.php' class='tooltipped' data-position='bottom' data-delay='50' data-tooltip='Cerrar sesion'><i class='material-icons'>padlock</i></a></li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <ul class='side-nav' id='mobile-demo'>
                    <li class='tienda'><a href='tienda.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Tus datos'><i class='material-icons blue-text text-darken-4'>person_pin</i></a></li>
                    <li class='nosotros'><a href='nosotros.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Tus compras'><i class='material-icons blue-text text-darken-4'>shopping_cart</i></a></li>
                    <li class='informacion'><a href='informacion.php' class='tooltipped' data-position='right' data-delay='50' data-tooltip='Cerrar sesion''><i class='material-icons blue-text text-darken-4'>padlock</i></a></li>
                </ul>
            ");
        }

        //Metodo para convertir el valor del mes en el nombre
        public static function Mes($valor)
        {
            switch ($valor) 
            {
                case 1:
                    return "Enero";
                    break;

                case 2:
                    return "Febrero";
                    break;

                case 3:
                    return "Marzo";
                    break;

                case 4:
                    return "Abril";
                    break;

                case 5:
                    return "Mayo";
                    break;

                case 6:
                    return "Junio";
                    break;

                case 7:
                    return "Julio";
                    break;

                case 8:
                    return "Agosto";
                    break;

                case 9:
                    return "Septiembre";
                    break;

                case 10:
                    return "Octubre";
                    break;

                case 11:
                    return "Noviembre";
                    break;

                case 12:
                    return "Diciembre";
                    break;
                
                default:
                    break;
            }
        }
    }
?>