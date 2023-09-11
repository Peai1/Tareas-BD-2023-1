<?php
	include_once 'header2.php';
	require 'connection.php';
?>

<body>
<link rel="stylesheet" href="../css/wishlist.css">

<?php
	echo "<center>";
	echo "<h1>Carrito</h1>";
	echo "</center>";
	$query_usuario = "SELECT rut, descuento FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta_usuario = mysqli_query($conn,$query_usuario);
	$usuario = mysqli_fetch_assoc($consulta_usuario);

	$query = "SELECT * FROM carrito WHERE rut_usuario = '".$usuario["rut"]."'";
	$consulta = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($consulta)) {
		if ($row["id_hotel"] != NULL) {
			$query = "SELECT * FROM hoteles WHERE id_hotel = ".$row["id_hotel"]."";
			$consulta2 = mysqli_query($conn, $query);
			$rows = mysqli_fetch_assoc($consulta2);
			echo "<div class='store'>";
			echo "<div class='objeto'>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='ids' value='".$row["id_hotel"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
			echo "<div class='texto' style='margin-top: 40px;'>";
			echo "<p>Nombre: ".$rows["nombre"]."</p><br>";
			echo "<p>Precio por noche: $".(int)$rows["precio_noche"]."</p><br>";
			echo "<p>Precio total: $".(int)$row["pago_total"]." (".$row["cantidad"]."x noches)</p><br>";
			echo "<form action='editar_carrito.php' method='post'>";
			echo "<input type='hidden' name='nro_item' value='".$row["nro_item"]."'>";
			echo "<input type='hidden' name='rut_usuario' value='".$usuario["rut"]."'>";
			echo "<button type='submit' name='editar_c'>Editar</button>";
			echo "</form>";
			echo "<br>";
			echo "<form action='eliminar_carrito.php' method='post'>";
			echo "<input type='hidden' name='nro_item' value='".$row["nro_item"]."'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='id_s' value='".$row["id_hotel"]."'>";
			echo "<button type='submit' name='eliminar_c'>Eliminar</button>";
			echo "</form>";
			echo "</div>";
			echo "</div>";
		}
		else {
			$query = "SELECT * FROM paquetes WHERE id_paquete = ".$row["id_paquete"]."";
			$consulta2 = mysqli_query($conn, $query);
			$rows = mysqli_fetch_assoc($consulta2);
			echo "<div class='store'>";
			echo "<div class='objeto'>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='ids' value='".$row["id_paquete"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";		
			echo "<div class='texto' style='margin-top: 40px;'>";
			echo "<p>Nombre: ".$rows["nombre"]."</p><br>";
			echo "<p>Precio por persona: $".(int)$rows["precio_persona"]."</p><br>";
			echo "<p>Precio total: $".(int)$row["pago_total"]." (".$row["cantidad"]."x personas)</p><br>";
			echo "<form action='editar_carrito.php' method='post'>";
			echo "<input type='hidden' name='nro_item' value='".$row["nro_item"]."'>";
			echo "<input type='hidden' name='rut_usuario' value='".$usuario["rut"]."'>";	
			echo "<button type='submit' name='editar_c'>Editar</button>";
			echo "</form>";
			echo "<br>";
			echo "<form action='eliminar_carrito.php' method='post'>";
			echo "<input type='hidden' name='nro_item' value='".$row["nro_item"]."'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='id_s' value='".$row["id_paquete"]."'>";
			echo "<button type='submit' name='eliminar_c'>Eliminar</button>";
			echo "</form>";
			echo "</div>";
			echo "</div>";
		}
	}
	$query_total = "SELECT SUM(pago_total) AS total FROM carrito WHERE rut_usuario = '".$usuario["rut"]."'";
	$consulta_total = mysqli_query($conn, $query_total);
	$precio_comp = mysqli_fetch_assoc($consulta_total);
	echo "<div class='compra_total'>";
	echo "<br>";
	echo "<p>Subtotal: $".(int)$precio_comp["total"]."</p><br>";
	if ($usuario["descuento"] == 0) {
		echo "<p>Descuentos: 0%</p><br>";
		echo "<p>Total: $".(int)$precio_comp["total"]."</p><br>";
	}
	else {
		echo "<p>Descuentos: 10%</p><br>";
		echo "<p>Total: $".(int)$precio_comp["total"]*(0.9)."</p><br>";
	}
	echo "<form action='pagar.php' method='post'>";
	echo "<input type='hidden' name='pago' value='".$usuario["rut"]."'>";
	echo "<button type='submit' name='pagar'>Pagar</button>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>