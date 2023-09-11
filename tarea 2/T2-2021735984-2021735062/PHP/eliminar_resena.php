<?php
	include_once 'header2.php';
	require 'connection.php';   
?>

<?php
	$query_r = "SELECT * FROM resenas WHERE id_resena = '".$_POST["delete_r"]."'";
	$consulta_r = mysqli_query($conn, $query_r);
	$row_r = mysqli_fetch_assoc($consulta_r);

	if ($row_r["id_hotel"] != NULL) {
		$id_h = $row_r["id_hotel"];
		$query_hotelProm = "SELECT AVG(calif_prom) AS promedio_estrellas FROM resenas WHERE id_hotel = '$id_h' AND id_resena != '".$_POST["delete_r"]."' GROUP BY id_hotel";
		$consulta_hotelProm = mysqli_query($conn, $query_hotelProm);
		$row_hotelProm = mysqli_fetch_assoc($consulta_hotelProm);
		$hotelProm = $row_hotelProm["promedio_estrellas"];

		$query_hoteles = "CALL actualizar_hp('$id_h', 'Hotel', '$hotelProm')";
		$consulta_hoteles = mysqli_query($conn, $query_hoteles);
	}
	else {
		$id_p = $row_r["id_paquete"];
		$query_paqueteProm = "SELECT AVG(calif_prom) AS promedio_estrellas FROM resenas WHERE id_paquete = '$id_p' AND id_resena != '".$_POST["delete_r"]."' GROUP BY id_paquete";
		$consulta_paqueteProm = mysqli_query($conn, $query_paqueteProm);
		$row_paqueteProm = mysqli_fetch_assoc($consulta_paqueteProm);
		$paqueteProm = $row_paqueteProm["promedio_estrellas"];

		$query_paquetes = "CALL actualizar_hp('$id_p', 'Paquete', '$paqueteProm')";
		$consulta_paquetes = mysqli_query($conn, $query_paquetes);
	}

	$query = "DELETE FROM resenas WHERE id_resena = '".$_POST["delete_r"]."'";
	$consulta = mysqli_query($conn, $query);

	echo "<center>";
	echo "<p>Se ha eliminado satisfactoriamente la reseña</p>";
	echo "<br>";
	echo "<form action='store.php' method='post'>";
	echo "<input type='hidden' name='h_p' value='".$_POST["tipo"]."'>";
	echo "<input type='hidden' name='ids' value='".$_POST["id_s"]."'>";
	echo "<button type='submit' name='wish'>Volver</button><br>";
	echo "</form>";
	echo "</center>";