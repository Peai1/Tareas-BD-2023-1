<?php
	include_once 'header2.php';
	require 'connection.php';   
?>

<?php
	$id = $_POST["id_s"];

	$query_usuario = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta_usuario = mysqli_query($conn, $query_usuario);
	$usuario = mysqli_fetch_assoc($consulta_usuario);
	$rut_usuario = $usuario["rut"];

	if ($_POST["h_p"] == 'Hotel') {
		$query = "DELETE FROM wishlist WHERE id_hotel = '$id' AND rut_usuario = '$rut_usuario'";
		$consulta = mysqli_query($conn, $query);
	}
	else {
		$query = "DELETE FROM wishlist WHERE id_paquete = '$id' AND rut_usuario = '$rut_usuario'";
		$consulta = mysqli_query($conn, $query);
	}
	header("Location: wishlist2.php");