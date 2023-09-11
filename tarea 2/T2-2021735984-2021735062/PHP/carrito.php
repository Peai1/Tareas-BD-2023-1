<?php
	include_once 'header2.php';
	require 'connection.php';
?>

<?php
	$query = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($consulta);
	echo $row;

	# Veo el mayor numero de nro_item para el usuario para asi el que se ingrese, sea 1 mas
	$query_aux = "SELECT MAX(nro_item) AS max_nro_item FROM carrito WHERE rut_usuario = '".$row["rut"]."' ";
	$result_aux = mysqli_query($conn, $query_aux);
	$row_aux = mysqli_fetch_assoc($result_aux);
	$max_nro_item = $row_aux["max_nro_item"];
	$nuevo_nro_item = $max_nro_item + 1;

	if ($_POST["tipo"] == "Hotel") {
		$query_precio = "SELECT precio_noche, habitaciones_dispo FROM hoteles WHERE id_hotel = '".$_POST["id_s"]."'";
		$consulta_precio = mysqli_query($conn, $query_precio);
		$precio = mysqli_fetch_assoc($consulta_precio);
		$cantidad = $_POST["cantidad"];
		$precio_total = $precio["precio_noche"] * $cantidad;

		$nueva_cant = $precio["habitaciones_dispo"] - 1;
		if ($nueva_cant < 0) {
			$query_dispo = "UPDATE hoteles SET habitaciones_dispo = 0 WHERE id_hotel = '".$_POST["id_s"]."'";
			$consulta_dispo = mysqli_query($conn, $query_dispo);
			header("Location: disponibilidad.php");
		}
		else {
			$query_dispo = "UPDATE hoteles SET habitaciones_dispo = $nueva_cant WHERE id_hotel = '".$_POST["id_s"]."'";
			$consulta_dispo = mysqli_query($conn, $query_dispo);

			$query = "INSERT INTO carrito (rut_usuario, id_hotel, pago_unitario, cantidad, pago_total, nro_item) VALUES(".$row["rut"].", ".$_POST["id_s"].", ".$precio["precio_noche"].", $cantidad, $precio_total, $nuevo_nro_item)";
			$consulta = mysqli_query($conn, $query);
			header("Location: carrito2.php");
		}
	}
	else {
		$query_precio = "SELECT precio_persona, numero_paquetesdispo FROM paquetes WHERE id_paquete = '".$_POST["id_s"]."'";
		$consulta_precio = mysqli_query($conn, $query_precio);
		$precio = mysqli_fetch_assoc($consulta_precio);
		$cantidad = $_POST["cantidad"];
		$precio_total = $precio["precio_persona"] * $cantidad;

		$nueva_cant = $precio["numero_paquetesdispo"] - 1;
		if ($nueva_cant < 0) {
			$query_dispo = "UPDATE paquetes SET numero_paquetesdispo = 0 WHERE id_paquete = '".$_POST["id_s"]."'";
			$consulta_dispo = mysqli_query($conn, $query_dispo);
			header("Location: disponibilidad.php");
		}
		else {
			$query_dispo = "UPDATE paquetes SET numero_paquetesdispo = $nueva_cant WHERE id_paquete = '".$_POST["id_s"]."'";
			$consulta_dispo = mysqli_query($conn, $query_dispo);

			$query = "INSERT INTO carrito (rut_usuario, id_paquete, pago_unitario, cantidad, pago_total, nro_item) VALUES(".$row["rut"].", ".$_POST["id_s"].", ".$precio["precio_persona"].", $cantidad, $precio_total, $nuevo_nro_item)";
			$consulta = mysqli_query($conn, $query);
			header("Location: carrito2.php");
		}
	}
?>