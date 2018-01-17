<?php
    require("../lib/renders.php");

    Render::Header("inicio");

    if(!empty($_SESSION["id"]))
    {
        Render::Navbar(0);
    }

    else
    {
        Render::Navbar(null);
    }   
?>

<!--Se crea el parallax para el inicio-->
        <div class="parallax-container valign-wrapper">   
            <div class="row valign">
                <div class="col s12">              
                    <h4 class="grey-text text-lighten-3 center-align">Somos los numero 1 dristribuyendo terminales en centro america</h4>            
                </div>
                <!--Boton para ir al login-->
                <div class="col s12 m4 offset-m2 mar">
                    <div class="center-align">
                        <a href="login.php" class="waves-effect waves-light btn blue darken-4">Registrate</a>
                    </div>                
                </div>
                <!--Aqui termina el boton que lleva al login-->

                <!--Boton para ir a la tienda-->
                <div class="col s12 m4 mar">
                    <div class="center-align">
                        <a href="tienda.php" class="waves-effect waves-light btn blue darken-4">Hecha un vistazo</a>
                    </div>                
                </div>
                <!--Aqui termina el boton de la tienda-->
            </div>
            
            <div class="parallax"><img src="../img/parallax/002.jpg"></div>
        </div>
        <!--Se termina el parallax  del inicio-->

<?php
    Render::Body();
?>