<?php
	include_once 'header2.php';
	require 'connection.php';
?>

<?php
	$query = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
    	$consulta = mysqli_query($conn, $query);
    	$row = mysqli_fetch_assoc($consulta);
	
	if ($_POST["h_p"] == "Hotel") {
		$query = "DELETE FROM carrito WHERE id_hotel = '".$_POST["id_s"]."' AND nro_item = '".$_POST["nro_item"]."' AND rut_usuario = '".$row["rut"]."'";
		$consulta = mysqli_query($conn, $query);
	}
	else {
		$query = "DELETE FROM carrito WHERE id_paquete = '".$_POST["id_s"]."' AND nro_item = '".$_POST["nro_item"]."' AND rut_usuario = '".$row["rut"]."'";
		$consulta = mysqli_query($conn, $query);
	}
	header("Location: carrito2.php");