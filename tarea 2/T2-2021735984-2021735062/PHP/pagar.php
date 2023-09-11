<?php
	include_once 'header2.php';
	require 'connection.php';
?>

<?php
	$query_carrito = "SELECT id_hotel, id_paquete FROM carrito WHERE rut_usuario = '".$_POST["pago"]."'";
	$consulta_carrito = mysqli_query($conn, $query_carrito);
	while ($row_carrito = mysqli_fetch_assoc($consulta_carrito)) {
		if ($row_carrito["id_hotel"] != NULL) {
			$query_compras = "SELECT * FROM compras WHERE id_hotel = '".$row_carrito["id_hotel"]."'";
			$consulta_compras = mysqli_query($conn, $query_compras);
			$row_compras = mysqli_fetch_all($consulta_compras, MYSQLI_ASSOC);
			if (sizeof($row_compras) <= 1) {
				$query_insert = "INSERT INTO compras (rut_usuario, id_hotel) VALUES('".$_POST["pago"]."', '".$row_carrito["id_hotel"]."')";
				$consulta_insert = mysqli_query($conn, $query_insert);
			}
		}
		else {
			$query_compras = "SELECT id_paquete FROM compras WHERE id_paquete = '".$row_carrito["id_paquete"]."'";
			$consulta_compras = mysqli_query($conn, $query_compras);
			$row_compras = mysqli_fetch_all($consulta_compras, MYSQLI_ASSOC);
			if (sizeof($row_compras) <= 1) {
				$query_insert = "INSERT INTO compras (rut_usuario, id_paquete) VALUES('".$_POST["pago"]."', '".$row_carrito["id_paquete"]."')";
				$consulta_insert = mysqli_query($conn, $query_insert);
			}
		}
	}

	$query = "DELETE FROM carrito WHERE rut_usuario = '".$_POST["pago"]."'";
	$consulta = mysqli_query($conn, $query);
	echo "<br>";
	echo "<center>";
	echo "<h1>Pago exitoso</h1>";
	echo "<br>";
	echo "<form action='index2.php' method='post'>";
	echo "<button type='submit' name='inicio'>Volver a la pagina principal</button>";
	echo "</form>";
	echo "</center>";