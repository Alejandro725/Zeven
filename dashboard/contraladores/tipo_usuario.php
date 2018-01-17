<?php
    //clase con las opciones para los tipos de usuario
    class opcionesTiposUsuarios
    {
        //metodo para renderizar las opciones de los tipos
        public static function renderTiposUsuarios()
        {
            //Variable que contiene los privilegios del usuario

            $datos_tipo = Sentencias::Seleccionar("tipos_usuarios", "id", array($_SESSION["tipo"]), 0, null);

            if(!empty($_POST))
            {
                //Aqui se pondran las variables de cada privilegio
                $dato_marca = $_POST["marcas"];
                $dato_producto = $_POST["productos"];
                $dato_usuario = $_POST["usuarios"];
                $dato_noticia = $_POST["noticias"];
                $dato_cliente = $_POST["clientes"];
                $dato_comentario = $_POST["comentarios"];
                $dato_video = $_POST["videos"];
                $dato_pedido = $_POST["pedidos"];
                
                if($_POST["tipo"] != "")
                {
                    if(Validaciones::nombre($_POST["tipo"]))
                    {
                        if(Validaciones::longitud($_POST["tipo"], 35))
                        {
                            $campos_valores = array
                            (
                                'tipo' => $_POST["tipo"],
                                'marcas' => $dato_marca,
                                'productos' => $dato_producto,
                                'usuarios' => $dato_usuario,
                                'noticias' => $dato_noticia,
                                'clientes' => $dato_cliente,
                                'comentarios' => $dato_comentario,
                                'videos' => $dato_video,
                                'pedidos' => $dato_pedido 
                            );

                            Sentencias::Insertar("tipos_usuarios", $campos_valores, 1, "tipos_usuarios.php");
                        }

                        else
                        {
                            Ventanas::Mensaje(2, "El nombre del tipo no es valido", null); 
                        }
                    }

                    else
                    {
                        Ventanas::Mensaje(2, "El nombre del tipo no es valido", null);  
                    }
                }

                else
                {
                    Ventanas::Mensaje(2, "No puede dejar vacio el campo de tipo", null);
                }
            }

            echo
            ("
                <div class='row' id='usuarios'>
                    <div class='col s10 offset-s1'>
                        <ul class='tabs'>
            ");

            //Aqui se renderiza segun los privilegios del usuario.
            //Renderiza solo para vista, crear y modificar.
            if($datos_tipo["usuarios"] > 0)
            {
                echo
                ("
                    <li class='tab col s6'><a class='active blue-text text-darken-4' href='#lista'>Lista de tipos</a></li>
                ");
            }

            if($datos_tipo["usuarios"] == 2 || $datos_tipo["usuarios"] == 9)
            {
                echo
                ("
                    <li class='tab col s6'><a class='blue-text text-darken-4' href='#registro'>Agregar tipo de usuario</a></li>
                ");
            }

            echo
            ("
                        </ul>
                    </div>
                </div>
                <form method='post' id='registro'>
                    <div class='row'>
                        <div class='container'>
                            <div class='col s12 l10 offset-l1 center-align'>
                                <h4>Selecciona los permisos para el usuario</h4>
                            </div>
                            <div class='input-field col s10 offset-s1 l8'>
                                <input id='tipo' name='tipo' type='text' class='validate'>
                                <label for='tipo' class='blue-text text-darken-4'>Tipo</label>
                            </div>
            ");

            //Se generan los combobox para cada permiso
            Render::ComboPermisos("marcas");
            Render::ComboPermisos("productos");
            Render::ComboPermisos("usuarios");
            Render::ComboPermisos("noticias");
            Render::ComboPermisos("clientes");
            Render::ComboPermisos("comentarios");
            Render::ComboPermisos("videos");
            Render::ComboPermisos("pedidos");

            echo
            ("
                        </div>
                    </div>
                    <div class='row'>
                        <div class='container'>
                            <div class='col s4 offset-s1 l4'>
                                <button class='waves-effect waves-light btn blue darken-4'>Agregar</button>
                            </div>
                        </div>  
                    </div>         
                </form>
            ");

            //Aqui se renderiza la tabla
            /*Aqui  se mostraran los tipos ya agregados*/
            echo
            ("
                <div class='col s12' id='lista'>
                    <div class='col s12 center-align'>
                        <h4>Lista de tipos</h4>
                    </div>

                    <table class='highlight centered col s12'>
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Información</th>
                            </tr>
                        </thead>
                    <tbody>
            ");

            //Variable con todos los tipos
            $datos_tipos = Sentencias::Seleccionar("tipos_usuarios", 0, null, null, null);

            //Aqui se genera la tabla para ver los tipos disponibles
            foreach($datos_tipos as $row_tipos)
            {
                echo "<tr>";

                echo
                ("
                    <td>".$row_tipos['tipo']."</td>
                    <td>
                        <a href='#modal".$row_tipos['id']."' class='blue-text text-darken-4'><i class='material-icons'>visibility</i></a>
                    </td>
                ");

                echo "</tr>";

                //Variables con el valor de permisos en string
                $marcas = self::Privilegios($row_tipos["marcas"]);
                $productos = self::Privilegios($row_tipos["productos"]);
                $usuarios = self::Privilegios($row_tipos["usuarios"]);
                $noticias = self::Privilegios($row_tipos["noticias"]);             
                $clientes = self::Privilegios($row_tipos["clientes"]);
                $comentarios = self::Privilegios($row_tipos["comentarios"]);
                $videos = self::Privilegios($row_tipos["videos"]);
                $pedidos = self::Privilegios($row_tipos["pedidos"]);

                //Aqui se renderiza el modal con la informacion de cada tipo
                echo
                ("
                    <div id='modal".$row_tipos['id']."' class='modal'>
                        <div class='modal-content'>
                        <h4>Privilegios para el tipo de usuario: ".$row_tipos['tipo']."</h4>
                        <p>Marcas: $marcas</p>
                        <p>Productos: $productos</p>
                        <p>Usuarios: $usuarios</p>
                        <p>Noticias: $noticias</p>
                        <p>Clientes: $clientes</p>
                        <p>Comentarios: $comentarios</p>
                        <p>Videos: $videos</p>
                        <p>Pedidos: $pedidos</p>
                        </div>
                        <div class='modal-footer'>
                        <a href='#!' class='modal-action modal-close waves-effect waves-green btn-flat'>Ok</a>
                        </div>
                    </div>
                ");
            }

            //Variable con todos los tipos
            /*$cantidad_tipos = Sentencias::Count("tipos_usuarios");

            $total = count($cantidad_tipos);
            $division = $total / 10;
            $cantidad = ceil($division);
            echo $cantidad;*/
        }

        //Metodo para regresar los privilegios de usuario en string
        public static function Privilegios($valor)
        {
            if($valor == 0)
            {
                return "Nulo";
            }

            if($valor == 1)
            {
                return "Solo lectura";
            }

            if($valor == 2)
            {
                return "Solo crear";
            }

            if($valor == 3)
            {
                return "Solo modificar";
            }

            if($valor == 4)
            {
                return "Solo eliminar";
            }

            if($valor == 5)
            {
                return "Crear y modificar";
            }

            if($valor == 6)
            {
                return "Crear y eliminar";
            }

            if($valor == 7)
            {
                return "Modificar y eliminar";
            }

            if($valor == 9)
            {
                return "Todos los permisos";
            }
        }
    }
?>