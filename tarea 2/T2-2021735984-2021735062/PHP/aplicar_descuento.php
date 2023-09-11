<?php
	include_once 'header2.php';
	require 'connection.php';   
?>

<?php
	$query = "UPDATE usuarios SET descuento = 1 WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta = mysqli_query($conn, $query);
	header("Location: carrito2.php");