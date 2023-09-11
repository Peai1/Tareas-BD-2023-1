<?php
	include_once 'header2.php';
	require 'connection.php';   
?>
<body>
<link rel="stylesheet" href="../css/store.css">
<?php
	$query = "SELECT rut FROM usuarios WHERE nombre = '".$_SESSION["session_user"]."'";
	$consulta = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($consulta);
	if ($_POST["tipo"] == "Hotel") {
		$query = "SELECT * FROM wishlist WHERE id_hotel = ".$_POST["id_s"]." AND rut_usuario = ".$row["rut"]."";
		$consulta = mysqli_query($conn, $query);
		$rows = mysqli_fetch_assoc($consulta);
		if ($rows == 0) {
			$query = "INSERT INTO wishlist (rut_usuario, id_hotel) VALUES(".$row["rut"].", ".$_POST["id_s"].")";
			$consulta = mysqli_query($conn, $query);
		}
	}
	else {
		$query = "SELECT * FROM wishlist WHERE id_paquete = ".$_POST["id_s"]."";
		$consulta = mysqli_query($conn, $query);
		$rows = mysqli_fetch_assoc($consulta);
		if ($rows == 0) {
			$query = "INSERT INTO wishlist (rut_usuario, id_paquete) VALUES(".$row["rut"].", ".$_POST["id_s"].")";
			$consulta = mysqli_query($conn, $query);
		}
	}
	header("Location: wishlist2.php");
?>
</body>
</html>