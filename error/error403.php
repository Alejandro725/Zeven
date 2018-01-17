<?php
    require("../lib/renders.php");
    require("../lib/ventanas.php"); 

    session_start();

    Render::Header("Error404");
    Render::Navbar(null);
?>
    <h4>No tienes permiso para esto</h4>
    <a href="../index.php">Volver al inicio</a>
<?php
    Render::Body();
?>