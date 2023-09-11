<?php
	include_once 'header2.php';
	require 'connection.php';   
?>
<body>
<link rel="stylesheet" href="../css/wishlist.css">
<?php
	$query_usuario = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta_usuario = mysqli_query($conn,$query_usuario);
	$usuario = mysqli_fetch_assoc($consulta_usuario);

	$query = "SELECT * FROM wishlist WHERE rut_usuario = '".$usuario["rut"]."'";
	$consulta = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($consulta)) {
		if ($row["id_hotel"] != NULL) {
			$query = "SELECT * FROM hoteles WHERE id_hotel = ".$row["id_hotel"]."";
			$consulta2 = mysqli_query($conn, $query);
			$rows = mysqli_fetch_assoc($consulta2);
			$query_hoteles = "SELECT *, calif_promedio AS promedio_estrellas FROM hoteles WHERE id_hotel = '".$row["id_hotel"]."' ORDER BY promedio_estrellas DESC LIMIT 10";
			$consulta_hoteles = mysqli_query($conn,$query_hoteles);
			$row_hoteles = mysqli_fetch_assoc($consulta_hoteles);
			echo "<div class='store'>";
			echo "<div class='objeto'>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='ids' value='".$row["id_hotel"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
			echo "<div class='texto'>";
			echo "<p>Nombre: ".$rows["nombre"]."</p><br>";
			if (is_null($row_hoteles) == True) {
				echo "<p>Puntuacion promedio: 0</p><br>";
			}
			else {
				echo "<p>Puntuacion promedio: ".number_format($row_hoteles["promedio_estrellas"],1)."</p><br>";
			}
			echo "<form action='eliminar_wishlist.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='id_s' value='".$row["id_hotel"]."'>";
			echo "<button type='submit' name='eliminar_w'>Eliminar</button>";
			echo "</form>";
			echo "</div>";
			echo "</div>";
			echo "<br>";
		}
		else {
			$query = "SELECT * FROM paquetes WHERE id_paquete = ".$row["id_paquete"]."";
			$consulta2 = mysqli_query($conn, $query);
			$rows = mysqli_fetch_assoc($consulta2);
			$query_paquetes = "SELECT *, calif_promedio AS promedio_estrellas FROM paquetes WHERE id_paquete = '".$row["id_paquete"]."' ORDER BY promedio_estrellas DESC LIMIT 10";
			$consulta_paquetes = mysqli_query($conn,$query_paquetes);
			$row_paquetes = mysqli_fetch_assoc($consulta_paquetes);
			echo "<div class='store'>";
			echo "<div class='objeto'>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='ids' value='".$row["id_paquete"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
			echo "<div class='texto'>";
			echo "<p>Nombre: ".$rows["nombre"]."</p><br>";
			if (is_null($row_paquetes) == True) {
				echo "<p>Puntuacion promedio: 0</p><br>";
			}
			else {
				echo "<p>Puntuacion promedio: ".number_format($row_paquetes["promedio_estrellas"],1)."</p><br>";
			}
			echo "<form action='eliminar_wishlist.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='id_s' value='".$row["id_paquete"]."'>";
			echo "<button type='submit' name='eliminar_w'>Eliminar</button>";
			echo "</form>";
			echo "</div>";
			echo "</div>";
		}
	}
?>
</body>
</html>