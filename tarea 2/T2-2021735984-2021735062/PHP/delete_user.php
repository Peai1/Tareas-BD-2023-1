<?php
	include_once 'header2.php';
	require 'connection.php';   
?>

<?php
	$query = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($consulta);

	$query = "DELETE FROM usuarios WHERE rut = '".$row["rut"]."'";
	$consulta = mysqli_query($conn, $query);
	header("Location: index.php");