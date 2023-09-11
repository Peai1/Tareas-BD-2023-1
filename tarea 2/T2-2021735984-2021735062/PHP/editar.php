<?php
	include_once 'header2.php';
	require 'connection.php';

// editar.php
    require 'connection.php';   
    echo "<center>";
    if (isset($_GET['campo'])) {
        $campo = $_GET['campo'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener el valor del campo enviado por el formulario
            $nuevoValor = $_POST[$campo];

            // Actualizar el campo en la base de datos
            $current_user = $_SESSION["session_user"];
            $query = "UPDATE usuarios SET $campo = '$nuevoValor' WHERE nombre = '$current_user'";
            mysqli_query($conn, $query);

            // Actualizar la variable de sesión con el nuevo nombre del usuario si se da el caso
            if ($campo == "nombre")
                $_SESSION["session_user"] = $nuevoValor;
    
            // Redirigir a la página de perfil
            header("Location: profile.php");
            exit();
        }

        if ($campo == "nombre")
            $campoAux = "Usuario";
        elseif ($campo == "fecha_nacimiento")
            $campoAux = "Fecha de nacimiento";
        elseif ($campo == "password")
            $campoAux = "Contraseña";
        elseif ($campo == "correo")
            $campoAux = "Correo Electronico";

        // Generar el formulario correspondiente al campo seleccionado
        echo "<h1>Editar $campoAux </h1>";
        echo "<form action=\"editar.php?campo=$campo\" method=\"post\">";
        echo "<label>Actualizar $campoAux: </label>";
        if ($campo == "fecha_nacimiento") {
            echo "<input type=\"date\" name=\"$campo\">";
        } else {
            echo "<input type=\"text\" name=\"$campo\">";
        }
        echo "<input type=\"submit\" value=\"Guardar\">";
        echo "</form>";

    } else {
        // Si no se proporciona un campo válido, redirigir a otra página
        header("Location: profile.php");
        exit();
    }
    echo "</center>";
?>