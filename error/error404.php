<?php
    require("../lib/renders.php");
    require("../lib/ventanas.php"); 

    session_start();

    Render::Header("Error404");
    Render::Navbar(null);
?>
    <h4>Esta pagina no existe en el sitio web</h4>
    <a href="../index.php">Volver al inicio</a>
<?php
    Render::Body();
?>