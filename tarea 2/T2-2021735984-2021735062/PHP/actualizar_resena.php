<?php
	include_once 'header2.php';
	require 'connection.php';


if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["editacion"])) && ($_POST["editacion"] === "true")) {

    $id_resena = $_POST['id_resena'];
    $fecha_actual = date("Y-m-d");

    if ($_POST["tipo"] == 'Limpieza' or $_POST["tipo"] == 'Calidad de hoteles'){
        $query = "UPDATE resenas SET calificacion_1 = '".$_POST['calificacion']."', fecha = '$fecha_actual' WHERE id_resena = '$id_resena'";
    }
    elseif ($_POST["tipo"] == 'Servicio' or $_POST["tipo"] == 'Transporte'){
        $query = "UPDATE resenas SET calificacion_2 = '".$_POST['calificacion']."', fecha = '$fecha_actual' WHERE id_resena = '$id_resena'";
    }
    elseif ($_POST["tipo"] == 'Decoración' or $_POST["tipo"] == 'Servicio_paquete'){
        $query = "UPDATE resenas SET calificacion_3 = '".$_POST['calificacion']."', fecha = '$fecha_actual' WHERE id_resena = '$id_resena'";
    }
    elseif ($_POST["tipo"] == 'Calidad de las camas' or $_POST["tipo"] == 'Relación precio-calidad'){
        $query = "UPDATE resenas SET calificacion_4 = '".$_POST['calificacion']."', fecha = '$fecha_actual' WHERE id_resena = '$id_resena'";
    }
    elseif ($_POST["tipo"] == 'comentario'){
        $query = "UPDATE resenas SET comentario = '".$_POST['comentario']."', fecha = '$fecha_actual' WHERE id_resena = '$id_resena'";
    }
    $consulta = mysqli_query($conn, $query);
	
	$query_update = "UPDATE resenas SET calif_prom = (calificacion_1 + calificacion_2 + calificacion_3 + calificacion_4)/4 WHERE id_resena = '$id_resena'";
	$consulta_update = mysqli_query($conn, $query_update);

	$query_r = "SELECT * FROM resenas WHERE id_resena = '$id_resena'";
	$consulta_r = mysqli_query($conn, $query_r);
	$row_r = mysqli_fetch_assoc($consulta_r);

	if ($row_r["id_hotel"] != NULL) {
		$id_h = $row_r["id_hotel"];
		$query_hotelProm = "SELECT AVG(calif_prom) AS promedio_estrellas FROM resenas WHERE id_hotel = '$id_h' GROUP BY id_hotel";
		$consulta_hotelProm = mysqli_query($conn, $query_hotelProm);
		$row_hotelProm = mysqli_fetch_assoc($consulta_hotelProm);
		$hotelProm = $row_hotelProm["promedio_estrellas"];

		$query_hoteles = "CALL actualizar_hp('$id_h', 'Hotel', '$hotelProm')";
		$consulta_hoteles = mysqli_query($conn, $query_hoteles);
	}
	else {
		$id_p = $row_r["id_paquete"];
		$query_paqueteProm = "SELECT AVG(calif_prom) AS promedio_estrellas FROM resenas WHERE id_paquete = '$id_p' GROUP BY id_paquete";
		$consulta_paqueteProm = mysqli_query($conn, $query_paqueteProm);
		$row_paqueteProm = mysqli_fetch_assoc($consulta_paqueteProm);
		$paqueteProm = $row_paqueteProm["promedio_estrellas"];

		$query_paquetes = "CALL actualizar_hp('$id_p', 'Paquete', '$paqueteProm')";
		$consulta_paquetes = mysqli_query($conn, $query_paquetes);
	}

	echo "<center>";
	echo "<h1>Reseña actualizada con exito</h1><br>";
	echo "<form action='editar_resena.php' method='POST'>";
	echo "<input type='hidden' name='editar_r' value='$id_resena'>";
	echo "<button type='submit' name='boton'>Regresar</button>";
	echo "</form>";
	echo "</center>";
	
    }