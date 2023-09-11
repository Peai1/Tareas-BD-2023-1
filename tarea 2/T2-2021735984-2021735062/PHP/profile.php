<?php
	include_once 'header2.php'
?>
    <body>
	<link rel="stylesheet" href="../css/profile.css">
    <?php
        require 'connection.php';
        #session_start();
        $current_user = $_SESSION["session_user"];

        $query = "SELECT * FROM usuarios WHERE nombre = '$current_user'";
        $consulta = mysqli_query($conn, $query);

        echo "<center>";
        echo "<h1>";
        echo "Perfil del Usuario";
        echo "</h1>";
        
        if ($row = mysqli_fetch_row($consulta)) {   
            echo "Usuario: ";
            echo $row[1];
            echo " <a href='editar.php?campo=nombre'>";
            echo "<img src='../imagenes/lapiz.png' alt='Editar' width='16' height='16'>";
            echo "</a>";
            echo "<br>";
            echo "Fecha de nacimiento: ";
            echo $row[2];
            echo " <a href='editar.php?campo=fecha_nacimiento'>";
            echo "<img src='../imagenes/lapiz.png' alt='Editar' width='16' height='16'>";
            echo "</a>";
            echo "<br>";
            echo "Correo electrónico: ";
            echo $row[3];
            echo " <a href='editar.php?campo=correo'>";
            echo "<img src='../imagenes/lapiz.png' alt='Editar' width='16' height='16'>";
            echo "</a>";
            echo "<br>";
            echo "Contraseña: ********";
            echo " <a href='editar.php?campo=password'>";
            echo "<img src='../imagenes/lapiz.png' alt='Editar' width='16' height='16'>";
            echo "</a>";
        }
        echo "<br><br>";
	echo "<div>";
	echo "<form action='logout.php' method='post' style='display: inline'>";
        echo "  ";
        echo "<input type='submit' value='Cerrar Sesión'>";
        echo "</form>";
	echo "<br><br>";

        echo "<form action='delete_user.php' method='post'>";
	echo "<button type='submit' name='borrar'>Borrar cuenta</button>";
        echo "</form>";
	echo "</div>";

	//Reseñas
	echo "<br><h2>Reseñas</h2><br>";
	echo "<br><h3>Reseñas Hoteles</h3><br>";
        echo "</center>";

	$query_usuario = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta_usuario = mysqli_query($conn,$query_usuario);
	$usuario = mysqli_fetch_assoc($consulta_usuario);

	$query_hotel = "SELECT * FROM resenas WHERE id_hotel IS NOT NULL and rut_usuario = '".$usuario["rut"]."' ORDER BY fecha DESC";
	$query_paquete = "SELECT * FROM resenas WHERE id_paquete IS NOT NULL and rut_usuario = '".$usuario["rut"]."' ORDER BY fecha DESC";

	$consulta_hotel = mysqli_query($conn,$query_hotel);
	$consulta_paquete = mysqli_query($conn,$query_paquete);

	echo "<div id='container'>";
	while ($row_hotel = mysqli_fetch_assoc($consulta_hotel)) {
		// Mostrar información del hotel
		$query_n = "SELECT nombre FROM hoteles WHERE id_hotel = '".$row_hotel["id_hotel"]."'";
		$consulta_n = mysqli_query($conn,$query_n);
		$row_n = mysqli_fetch_assoc($consulta_n);
		echo "<div class='col1'>";
		echo "<br><p>Nombre Hotel: " . $row_n['nombre'] . "</p><br>";
		echo "<p>Limpieza: " . $row_hotel['calificacion_1'] . "</p><br>";
		echo "<p>Servicio: " . $row_hotel['calificacion_2'] . "</p><br>";
		echo "<p>Decoración: " . $row_hotel['calificacion_3'] . "</p><br>";
		echo "<p>Calidad de las camas: " . $row_hotel['calificacion_4'] . "</p><br>";
		echo "<p>Comentario: " . $row_hotel["comentario"] . "</p><br>";
		echo "<p>Fecha: " . $row_hotel["fecha"] . "</p>";
		echo "<br>";
		echo "</div>";
	}
	echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
	echo "<center>";
	echo "<br><h3>Reseñas Paquetes</h3><br>";
	echo "</center>";

	while ($row_paquete = mysqli_fetch_assoc($consulta_paquete)) {
		// Mostrar información del paquete
		$query_n = "SELECT nombre FROM paquetes WHERE id_paquete = '".$row_paquete["id_paquete"]."'";
		$consulta_n = mysqli_query($conn,$query_n);
		$row_n = mysqli_fetch_assoc($consulta_n);
		echo "<div class='col2'>";
		echo "<br><p>Nombre Paquete: " . $row_n['nombre'] . "</p><br>";
		echo "<p>Calidad de Hoteles: " . $row_paquete['calificacion_1'] . "</p><br>";
		echo "<p>Transporte: " . $row_paquete['calificacion_2'] . "</p><br>";
		echo "<p>Servicio: " . $row_paquete['calificacion_3'] . "</p><br>";
		echo "<p>Relacion precio-calidad: " . $row_paquete['calificacion_4'] . "</p><br>";
		echo "<p>Comentario: " . $row_paquete["comentario"] . "</p><br>";
		echo "<p>Fecha: " . $row_paquete["fecha"] . "</p>";
		echo "<br>";
		echo "</div>";
	}
	?>

	</div>
    </body>
</html>