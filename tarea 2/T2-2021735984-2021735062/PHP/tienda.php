<body>
<link rel="stylesheet" href="../css/tienda.css">

<?php
	include_once 'header2.php';
	require 'connection.php';   

$query_hotel = "SELECT *, calif_promedio AS promedio_estrellas FROM hoteles ORDER BY promedio_estrellas DESC LIMIT 10";
$query_paquete = "SELECT *, calif_promedio AS promedio_estrellas FROM paquetes ORDER BY promedio_estrellas DESC LIMIT 10";

$consulta_hotel = mysqli_query($conn,$query_hotel);
$consulta_paquete = mysqli_query($conn,$query_paquete);

echo "<center>";
echo "<br><h1>Top 10 Hoteles</h1><br>";

echo "<div id='container'>";
# Aquellos con calificacion mas alta en promedio, mostrar nombre, una imagen y puntuacion promedio.
while ($row_hotel = mysqli_fetch_assoc($consulta_hotel)) {
	// Mostrar información del hotel
	echo "<div class='col1'>";
	$query = "SELECT * FROM hoteles WHERE id_hotel = '".$row_hotel["id_hotel"]."'";
	$consulta = mysqli_query($conn,$query);
	$rows = mysqli_fetch_assoc($consulta);
	#echo "ID: " . $row_hotel["id_hotel"] . "<br>";
	echo "Nombre Hotel: " . $rows["nombre"] . "<br>";
	echo "Puntuación promedio: " . number_format($row_hotel["promedio_estrellas"],1) . "<br>";
	echo "<form action='store.php' method='post'>";
	echo "<input type='hidden' name='h_p' value='Hotel'>";
	echo "<input type='hidden' name='ids' value='".$row_hotel["id_hotel"]."'>";
	echo "<button type='submit' name='hotel_b' style='border-style:none;width:300px;height:220px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
	echo "</form>";
	echo "</div>";
}

echo "<br>";
echo "<br><h1 class='titulo'>Top 10 Paquetes</h1><br>";

while ($row_paquete = mysqli_fetch_assoc($consulta_paquete)) {
	echo "<div class='col2'>";
	$query = "SELECT * FROM paquetes WHERE id_paquete = '".$row_paquete["id_paquete"]."'";
	$consulta = mysqli_query($conn,$query);
	$rows = mysqli_fetch_assoc($consulta);
	echo "Nombre Paquete: " . $rows["nombre"] . "<br>";
	echo "Puntuación promedio: " . number_format($row_paquete["promedio_estrellas"],1) . "<br>";
	echo "<form action='store.php' method='post'>";
	echo "<input type='hidden' name='h_p' value='Paquete'>";
	echo "<input type='hidden' name='ids' value='".$row_paquete["id_paquete"]."'>";
	echo "<button type='submit' name='hotel_b' style='border-style:none;width:300px;height:220px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
	echo "</form>";
	echo "</div>";
}

echo "</div>";
echo "</center>";
?>
</body>