<?php
	include_once 'header2.php';
	require 'connection.php';   

echo "<br><br><center>";

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["seccion_editar"])) && ($_POST["seccion_editar"] === "true")) {

        $campo = $_POST['campo'];
        $id_resena = $_POST['id_resena'];
        if ($campo == 'calif_1' or $campo == 'calif_2' or $campo == 'calif_3' or $campo == 'calif_4') {
            // Mostrar el campo 'nombre' para editar
            echo "<p> Elegir nueva calificación de ".$_POST['tipo']." </p><br>";
            echo "<form action='actualizar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id_resena."'>";
            echo "<input type='hidden' name='tipo' value='".$_POST['tipo']."'>";
            echo "<input type='hidden' name='editacion' value='true'>";
            echo "<label for='calificacion'>Seleccione una: </label>";
            echo "<select name='calificacion' id='calificacion'>";
            echo "<option value='1'>1 estrella</option>";
            echo "<option value='2'>2 estrellas</option>";
            echo "<option value='3'>3 estrellas</option>";
            echo "<option value='4'>4 estrellas</option>";
            echo "<option value='5'>5 estrellas</option>";
            echo "</select><br>";
            echo "<br><input type='submit' value='Enviar'></form>";

        } 
        elseif ($campo == 'comentario') {
            // Mostrar el campo 'comentario' para editar
            echo "<form action='actualizar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id_resena."'>";
		echo "<input type='hidden' name='tipo' value='$campo'>";
            echo "<input type='hidden' name='editacion' value='true'>";
            echo "<label for='resena'>Nuevo comentario: </label><br><br>";
		    echo "<textarea name='comentario' id='comentario' rows='4' cols='50'></textarea><br>";
            echo "<br><input type='submit' value='Enviar'></form>";
        } 
    }
    
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

        echo "<p> Para editar la reseña, haga click en el icono del lapiz del campo que desea modificar</p><br>";
        $id = $_POST["editar_r"];
        $query = "SELECT * FROM resenas WHERE id_resena = '".$_POST["editar_r"]."'";
        $consulta = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($consulta);
        echo "Realizada por: " . $_SESSION["session_user"]."<br><br>";

        if(isset($row['id_hotel'])){
            echo "<p>Limpieza: " . $row['calificacion_1'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='tipo' value='Limpieza'>";
            echo "<input type='hidden' name='campo' value='calif_1'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";

			echo "<p>Servicio: " . $row['calificacion_2'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='tipo' value='Servicio'>";
            echo "<input type='hidden' name='campo' value='calif_2'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";

			echo "<p>Decoración: " . $row['calificacion_3'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='tipo' value='Decoración'>";
            echo "<input type='hidden' name='campo' value='calif_3'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";

			echo "<p>Calidad de las camas: " . $row['calificacion_4'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='tipo' value='Calidad de las camas'>";
            echo "<input type='hidden' name='campo' value='calif_4'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";
        }
        else{
            echo "<p>Calidad de hoteles: " . $row['calificacion_1'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='campo' value='calif_1'>";
            echo "<input type='hidden' name='tipo' value='Calidad de hoteles'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";

			echo "<p>Transporte: " . $row['calificacion_2'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='campo' value='calif_2'>";
            echo "<input type='hidden' name='tipo' value='Transporte'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";

			echo "<p>Servicio: " . $row['calificacion_3'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='campo' value='calif_3'>";
            echo "<input type='hidden' name='tipo' value='Servicio_paquete'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";

			echo "<p>Relación precio-calidad: " . $row['calificacion_4'] . "</p><br>";
            echo "<form action='editar_resena.php' method='post'>";
            echo "<input type='hidden' name='id_resena' value='".$id."'>";
            echo "<input type='hidden' name='campo' value='calif_4'>";
            echo "<input type='hidden' name='tipo' value='Relación precio-calidad'>";
            echo "<input type='hidden' name='seccion_editar' value='true'>";
            echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
            echo "</form><br>";
        }
        
        echo "<p>Comentario: " . $row["comentario"] . "";
        echo "<form action='editar_resena.php' method='post'>";
        echo "<input type='hidden' name='id_resena' value='".$id."'>";
        echo "<input type='hidden' name='campo' value='comentario'>";
        echo "<input type='hidden' name='seccion_editar' value='true'>";
        echo "<button type='submit' name='hotel_b'><img src=../imagenes/lapiz.png width=10 alt='Imagen'></button>";
        echo "</form><br>";
        
        echo "<p>Fecha: " . $row["fecha"] . "";		
	}

echo "</center>";
?>