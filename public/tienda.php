<?php
    require("../lib/database.php");
    require("../lib/renders.php");
    require("../lib/sentencias.php");
    require("../lib/ventanas.php"); 
    require("../lib/validaciones.php");
    require("controladores/tienda.php");

    session_start();

    Render::Header("Tienda");
    opcionesTienda::Añadir();
    Render::Navbar(null);
?>
    <div class='row mar'>
        <!--Aqui se crea el formulario para busqueda-->
        <form method='post' class="col s10 offset-s1 m4">

            <!--Se crea la barra de busqueda-->
            <div class="input-field">
                <i class="material-icons prefix">search</i>
                <input id="buscar" type="text" class="validate" name='buscar'>
                <label for="buscar">Buscar</label>
            </div>
            <!--Se termina la barra de busqueda-->

            <!--Se agrega un select para el orden-->
            <div class="input-field col s12 blue-text text-darken-4">    
                <select name='orden'>
                    <option value="0" selected>Orden Predeterminado</option>
                    <option value="1">Lo mas economico</option>
                    <option value="2">Lo mas costoso</option>
                </select>
            </div>
            <!--Se termina el select-->

            <!--Boton para las busquedas-->
            <div class='col s4 offset-s1 l4'>
                <button name='formulario' class='waves-effect waves-light btn blue darken-4'>Buscar</button>
            </div>
            <!--Aqui termina el boton de  las busquedas-->
        </form>
        <!--Aqui se termina el formulario para busqueda-->

        <?php
            opcionesTienda::renderProductos();
        ?>
    </div>

<?php    
    Render::Body();
?>