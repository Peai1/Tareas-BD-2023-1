<?php
    session_start(); // Inicia la sesión si no se ha iniciado antes

    // Destruye todas las variables de sesión
    session_unset();
    session_destroy();

    // Redirige al usuario a la página de inicio de sesión o a donde prefieras
    header("Location: index.php");
    exit;
?>