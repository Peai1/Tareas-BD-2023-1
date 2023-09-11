<?php
	include_once 'header2.php';
	include_once 'popup.php';
	require 'connection.php';   
?>
	<head>
	<link rel="stylesheet" href="../css/index.css">
	</head>
	<body>
	<center>
	<br>
	<img src="../imagenes/picture.jpg" alt="logo" height="200"> 
	<h1>Bienvenido a PrestigeTravels</h1>
	<br>
	<p>Hoteles y Paquetes con más reservas disponibles</p>
	<br><br>
	<?php
	// Consulta SQL para obtener los primeros cuatro hoteles con más reservas disponibles
	$query = "SELECT 'Paquete' AS tipo, id_paquete AS id, numero_paquetesdispo AS disponibles, nombre, precio_persona AS precio
			FROM paquetes
			UNION
			SELECT 'Hotel' AS tipo, id_hotel AS id, habitaciones_dispo AS disponibles, nombre, precio_noche AS precio
			FROM hoteles
			ORDER BY disponibles DESC
			LIMIT 4";

	// Ejecutar la consulta y obtener el resultado
	$consulta = mysqli_query($conn, $query);

	// Crear la tabla
	echo "<div class='cointainer'>";
	echo "<div class='grid-container'>";

	while ($row = mysqli_fetch_assoc($consulta)) {
		// Imprimir los datos de cada hotel
		#echo "ID: " . $row["id"] . "<br>";
		echo "<div class='image-container'>";
		echo "Tipo: " . $row["tipo"] . "<br>";
		echo "Nombre: " . $row["nombre"] . "<br>";
		if($row["tipo"] == "Paquete"){
			echo "Precio por persona: $" . (int)$row["precio"] . "<br>";
			echo "Número de paquetes disponibles: " . $row["disponibles"] . "<br>";
		}
		elseif($row["tipo"] == "Hotel"){
			echo "Precio por noche: $" . (int)$row["precio"] . "<br>";
			echo "Número de habitaciones disponibles: " . $row["disponibles"] . "<br>";
		}
		echo"<br><form action='store.php' method='post'>";
		echo "<input type='hidden' name='h_p' value='".$row["tipo"]."'>";
		echo "<input type='hidden' name='ids' value='".$row["id"]."'>";
		echo "<button type='submit' name='hotel_b' style='border-style:none;'><img src=../imagenes/hotel.jpg width=200 alt='Imagen'></button>";
		echo "</form>";
		echo "<br>";
		echo "</div>";
	}

	echo "</div>";
	echo "</div>";

	?>
	</center>
	</body>
</html>