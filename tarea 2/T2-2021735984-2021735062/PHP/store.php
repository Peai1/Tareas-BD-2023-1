<?php
	include_once 'header2.php';
	require 'connection.php';   
?>
<body>
<link rel="stylesheet" href="../css/store.css">
<?php

	// Guardar información de la reseña
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["seccion_resena"]) && $_POST["seccion_resena"] === "true") {
			$query = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
			$consulta = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($consulta);
			$id_s = $_POST["ids"];
			$calif_1 = $_POST["calif_1"];
			$calif_2 = $_POST["calif_2"];
			$calif_3 = $_POST["calif_3"];
			$calif_4 = $_POST["calif_4"];
			$calif_prom = ($calif_1 + $calif_2 + $calif_3 + $calif_4)/4;
			$comentario = $_POST["comentario"];
			if ($_POST["h_p"] == "Hotel"){
				$query = "INSERT INTO resenas (rut_usuario, id_hotel, comentario, calificacion_1, calificacion_2, calificacion_3, calificacion_4, calif_prom, fecha) VALUES (".$row["rut"].", $id_s, '$comentario', $calif_1, $calif_2, $calif_3, $calif_4, $calif_prom, NOW())";
				mysqli_query($conn, $query);

				$query_hotelProm = "SELECT AVG(calif_prom) AS promedio_estrellas FROM resenas WHERE id_hotel = '$id_s' GROUP BY id_hotel";
				$consulta_hotelProm = mysqli_query($conn, $query_hotelProm);
				$row_hotelProm = mysqli_fetch_assoc($consulta_hotelProm);
				$hotelProm = $row_hotelProm["promedio_estrellas"];

				$query_hoteles = "CALL actualizar_hp('$id_s', 'Hotel', '$hotelProm')";
				$consulta_hoteles = mysqli_query($conn, $query_hoteles);
			}
			else {
				$query = "INSERT INTO resenas (rut_usuario, id_paquete, comentario, calificacion_1, calificacion_2, calificacion_3, calificacion_4, calif_prom, fecha) VALUES (".$row["rut"].", $id_s, '$comentario', $calif_1, $calif_2, $calif_3, $calif_4, $calif_prom, NOW())";
				mysqli_query($conn, $query);

				$query_paqueteProm = "SELECT AVG(calif_prom) AS promedio_estrellas FROM resenas WHERE id_paquete = '$id_s' GROUP BY id_paquete";
				$consulta_paqueteProm = mysqli_query($conn, $query_paqueteProm);
				$row_paqueteProm = mysqli_fetch_assoc($consulta_paqueteProm);
				$paqueteProm = $row_paqueteProm["promedio_estrellas"];

				$query_paquetes = "CALL actualizar_hp('$id_s', 'Paquete', '$paqueteProm')";
				$consulta_paquetes = mysqli_query($conn, $query_paquetes);
			}			
		}
	}

	// Mostrar información
	if ($_POST["h_p"] == "Hotel") {
		$query = "SELECT * FROM hoteles WHERE id_hotel = ".$_POST["ids"]."";
		$consulta = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($consulta);
		#echo $row["id_hotel"];
		echo "<div class='store'>";
		echo "<div class='objeto'>";
		echo "<img src=../imagenes/hotel.jpg width=600 alt='Imagen'>";
		echo "</div>";
		echo "<div class='texto'>";
		echo "<p>Nombre: ".$row["nombre"]."</p><br>";
		echo "<p>Cantidad de estrellas: ".$row["num_estrellas"]."</p><br>";
		echo "<p>Ubicación: ".$row["ciudad"]."</p><br>";
		echo "<p>Habitaciones disponibles: ".$row["habitaciones_dispo"]."</p><br>";
		echo "<p>Precio por noche: $".(int)$row["precio_noche"]."</p><br>";
		if ($row["estacionamiento"] == 1) {
			echo "<p>Estacionamiento: Sí incluye</p><br>";
		}
		else {
			echo "<p>Estacionamiento: No incluye</p><br>";
		}
		if ($row["piscina"] == 1) {
			echo "<p>Piscina: Sí incluye</p><br>";
		}
		else {
			echo "<p>Piscina: No incluye</p><br>";
		}
		if ($row["lavanderia"] == 1) {
			echo "<p>Lavandería: Sí incluye</p><br>";
		}
		else {
			echo "<p>Lavandería: No incluye</p><br>";
		}
		if ($row["pet_friendly"] == 1) {
			echo "<p>Mascotas: Permitidas</p><br>";
		}
		else {
			echo "<p>Mascotas: No permitidas</p><br>";
		}
		if ($row["desayuno"] == 1) {
			echo "<p>Desayuno: Sí incluye</p><br>";
		}
		else {
			echo "<p>Desayuno: No incluye</p><br>";
		}
		echo "</div>";
		echo "<div class='buttons'>";
		echo "<form action='carrito.php' method='post'>";

		echo "<label for='cantidad'>Días de estadía: </label>";
		echo "<select name='cantidad' id='cantidad'>";
		echo "<option value='1'>1</option>";
		echo "<option value='2'>2</option>";
		echo "<option value='3'>3</option>";
		echo "<option value='4'>4</option>";
		echo "<option value='5'>5</option>";
		echo "<option value='6'>6</option>";
		echo "<option value='7'>7</option>";
		echo "</select>";
		echo " ";

		echo "<input type='hidden' name='tipo' value='".$_POST["h_p"]."'>";
		echo "<input type='hidden' name='id_s' value='".$_POST["ids"]."'>";
		echo "<button type='submit' name='carrito'>Añadir al carrito</button>";
		echo "</form>";
		echo "<br>";
		echo "<form action='wishlist.php' method='post'>";
		echo "<input type='hidden' name='tipo' value='".$_POST["h_p"]."'>";
		echo "<input type='hidden' name='id_s' value='".$_POST["ids"]."'>";
		echo "<button type='submit' name='wish'>Añadir a wishlist</button><br>";
		echo "</form>";
		echo "</div>";
		// Mostrar reseñas
		echo "<br><br><br>";
		$query = "SELECT * FROM resenas WHERE id_hotel = ".$row["id_hotel"]."";
		$result = mysqli_query($conn, $query);
		$contador = 1;
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<br>";
			$query_usuario = "SELECT nombre FROM usuarios WHERE rut = ".$row["rut_usuario"]."";
			$consulta_usuario = mysqli_query($conn, $query_usuario);
			$row_usuario = mysqli_fetch_assoc($consulta_usuario);
			echo "<p>Reseña número: " . $contador . "</p><br>";
			echo "<p>Por: " . $row_usuario['nombre'] . "</p><br>";
			echo "<p>Limpieza: " . $row['calificacion_1'] . "</p><br>";
			echo "<p>Servicio: " . $row['calificacion_2'] . "</p><br>";
			echo "<p>Decoración: " . $row['calificacion_3'] . "</p><br>";
			echo "<p>Calidad de las camas: " . $row['calificacion_4'] . "</p><br>";
			if ($row["comentario"] != NULL) {
				echo "<p>Comentario: " . $row["comentario"] . "</p><br>";
			}
			echo "<p>Fecha: " . $row["fecha"] . "</p>";
			if ($row_usuario["nombre"] == $_SESSION["session_user"]) {
				echo "<br>";
				echo "<form action='editar_resena.php' method='post'>";
				echo "<input type='hidden' name='editar_r' value='".$row["id_resena"]."'>";
				echo "<button type='submit' name='wish'>Editar</button><br>";
				echo "</form>";
				echo "<br>";
				echo "<form action='eliminar_resena.php' method='post'>";
				echo "<input type='hidden' name='tipo' value='".$_POST["h_p"]."'>";
				echo "<input type='hidden' name='id_s' value='".$_POST["ids"]."'>";
				echo "<input type='hidden' name='delete_r' value='".$row["id_resena"]."'>";
				echo "<button type='submit' name='wish'>Eliminar</button><br>";
				echo "</form>";
			}
			echo "<br><hr>";
			$contador = $contador + 1;
		}
	}
	else {
		$query = "SELECT * FROM paquetes WHERE id_paquete = ".$_POST["ids"]."";
		$consulta = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($consulta);
		#echo $row["id_paquete"];
		echo "<div class='store'>";
		echo "<div class='objeto'>";
		echo "<img src=../imagenes/hotel.jpg width=600 alt='Imagen'>";
		echo "</div>";
		echo "<div class='texto'>";
		echo "<p>Nombre: ".$row["nombre"]."</p><br>";
		echo "<p>Calificacion: ".$row["calif_promedio"]."</p><br>";
		echo "<p>Aerolinea de ida: ".$row["aerolinea_ida"]."</p><br>";
		echo "<p>Aerolinea de vuelta: ".$row["aerolinea_vuelta"]."</p><br>";
		echo "<p>Hoteles: ".$row["hospedaje_1"].", ".$row["hospedaje_2"].", ".$row["hospedaje_3"]."</p><br>";
		echo "<p>Ciudades: ".$row["ciudad_1"].", ".$row["ciudad_2"].", ".$row["ciudad_3"]."</p><br>";
		echo "<p>Precio por persona: $".$row["precio_persona"]."</p><br>";
		echo "<p>Cantidad maxima de personas: ".$row["maximo_personas"]."</p><br>";
		echo "</div>";
		echo "<div class='buttons'>";
		echo "<form action='carrito.php' method='post'>";

		echo "<label for='cantidad'>Cantidad de personas: </label>";
		echo "<select name='cantidad' id='cantidad'>";
		for ($i=1; $i<=$row["maximo_personas"]; $i++) {
			echo "<option value='$i'>$i</option>";
		}
		echo "</select>";
		echo " ";

		echo "<input type='hidden' name='tipo' value='".$_POST["h_p"]."'>";
		echo "<input type='hidden' name='id_s' value='".$_POST["ids"]."'>";
		echo "<button type='submit' name='carrito'>Añadir al carrito</button>";
		echo "</form>";
		echo "<br>";
		echo "<form action='wishlist.php' method='post'>";
		echo "<input type='hidden' name='tipo' value='".$_POST["h_p"]."'>";
		echo "<input type='hidden' name='id_s' value='".$_POST["ids"]."'>";
		echo "<button type='submit' name='wish'>Añadir a wishlist</button><br>";
		echo "</form>";
		echo "<br><br><br><br><br><br><br>";
		// Mostrar reseñas
		$query = "SELECT * FROM resenas WHERE id_paquete = ".$row["id_paquete"]."";
		$result = mysqli_query($conn, $query);
		$contador = 1;
		while ($row = mysqli_fetch_assoc($result)) {
			$query_usuario = "SELECT nombre FROM usuarios WHERE rut = ".$row["rut_usuario"]."";
			$consulta_usuario = mysqli_query($conn, $query_usuario);
			$row_usuario = mysqli_fetch_assoc($consulta_usuario);
			echo "<p>Reseña número: " . $contador . "</p><br>";
			echo "<p>Por: " . $row_usuario['nombre'] . "</p><br>";
			echo "<p>Calidad de Hoteles: " . $row['calificacion_1'] . "</p><br>";
			echo "<p>Transporte: " . $row['calificacion_2'] . "</p><br>";
			echo "<p>Servicio: " . $row['calificacion_3'] . "</p><br>";
			echo "<p>Relacion precio-calidad: " . $row['calificacion_4'] . "</p><br>";
			if ($row["comentario"] != NULL) {
				echo "<p>Comentario: " . $row["comentario"] . "</p><br>";
			}
			echo "<p>Fecha: " . $row["fecha"] . "</p>";
			if ($row_usuario["nombre"] == $_SESSION["session_user"]) {
				echo "<br>";
				echo "<form action='editar_resena.php' method='post'>";
				echo "<input type='hidden' name='editar_r' value='".$row["id_resena"]."'>";
				echo "<button type='submit' name='wish'>Editar</button><br>";
				echo "</form>";
				echo "<br>";
				
				echo "<form action='eliminar_resena.php' method='post'>";
				echo "<input type='hidden' name='tipo' value='".$_POST["h_p"]."'>";
				echo "<input type='hidden' name='id_s' value='".$_POST["ids"]."'>";
				echo "<input type='hidden' name='delete_r' value='".$row["id_resena"]."'>";
				echo "<button type='submit' name='wish'>Eliminar</button><br>";
				echo "</form>";
			}
			echo "<br><hr>";
			$contador = $contador + 1;
		}
	}
	// Form para reseñas
	if (isset($_SESSION["session_user"])) {
		$query_usuario = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
		$consulta_usuario = mysqli_query($conn, $query_usuario);
		$row_usuario = mysqli_fetch_assoc($consulta_usuario);
		$rut_usuario = $row_usuario["rut"];
		
		if ($_POST["h_p"] == "Hotel"){
			$query_hotel = "SELECT * FROM compras WHERE id_hotel = '".$_POST["ids"]."' and rut_usuario = '$rut_usuario'";
			$consulta_hotel = mysqli_query($conn, $query_hotel);
			$row_hotel = mysqli_fetch_assoc($consulta_hotel);

			if ($row_hotel != NULL) {

				echo "<div class='resena'>";
				echo "<form action='store.php' method='post'>";
				echo "<input type='hidden' name='h_p' value='".$_POST["h_p"]."'>";
				echo "<input type='hidden' name='ids' value='".$_POST["ids"]."'>";
				echo "<input type='hidden' name='seccion_resena' value='true'>";
				echo "<label for='resena'>Deja tu reseña:</label><br>";
				echo "<textarea name='comentario' id='comentario' rows='4' cols='50'></textarea><br>";


				echo "<div style='display:inline';>";

				echo "<label for='calificacion'>Limpieza: </label>";
				echo "<select name='calif_1' id='calif_1'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select> ";

				echo "<label for='calificacion'>Servicio: </label>";
				echo "<select name='calif_2' id='calif_2'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select> ";

				echo "<label for='calificacion'>Decoración: </label>";
				echo "<select name='calif_3' id='calif_3'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select> ";

				echo "<label for='calificacion'>Calidad de las camas: </label>";
				echo "<select name='calif_4' id='calif_4'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select> ";

				echo "</div><br>";

				echo "<input type='submit' value='Enviar'><br><br>";
				echo "</form>";
				echo "</div>";
			}

		}
		else {
			$query_paquete = "SELECT * FROM compras WHERE id_paquete = '".$_POST["ids"]."' and rut_usuario = '$rut_usuario'";
			$consulta_paquete = mysqli_query($conn, $query_paquete);
			$row_paquete = mysqli_fetch_assoc($consulta_paquete);

			if ($row_paquete != NULL) {

				echo "<div class='resena'>";
				echo "<form action='store.php' method='post'>";
				echo "<input type='hidden' name='h_p' value='".$_POST["h_p"]."'>";
				echo "<input type='hidden' name='ids' value='".$_POST["ids"]."'>";
				echo "<input type='hidden' name='seccion_resena' value='true'>";
				echo "<label for='resena'>Deja tu reseña:</label><br>";
				echo "<textarea name='comentario' id='comentario' rows='4' cols='50'></textarea><br>";


				echo "<div style='display:inline;'>";
				echo "<label for='calificacion'>Calidad de hoteles: </label>";
				echo "<select name='calif_1' id='calif_1'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select>";

				echo " <label for='calificacion'>Transporte: </label>";
				echo "<select name='calif_2' id='calif_2'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select>";

				echo " <label for='calificacion'>Servicio: </label>";
				echo "<select name='calif_3' id='calif_3'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select>";

				echo " <label for='calificacion'>Relación precio-calidad: </label>";
				echo "<select name='calif_4' id='calif_4'>";
				echo "<option value='1'>1 estrella</option>";
				echo "<option value='2'>2 estrellas</option>";
				echo "<option value='3'>3 estrellas</option>";
				echo "<option value='4'>4 estrellas</option>";
				echo "<option value='5'>5 estrellas</option>";
				echo "</select>";

				echo "</div><br>";

				echo "<input type='submit' value='Enviar'><br><br>";
				echo "</form>";
				echo "</div>";
			}
		}
	} 
	else {
		echo "<p>Debes iniciar sesión para dejar una reseña.</p>";
	}
?>
</body>
</html>