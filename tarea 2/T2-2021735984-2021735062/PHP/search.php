<?php
	include_once 'header2.php';
	require 'connection.php';   
?>

<body>
<link rel="stylesheet" href="../css/search.css">

<center>
<form action="search.php" method="post">
<input type='hidden' name='filtros' value='true'>
<?php echo "<input type='hidden' name='search' value='".$_POST["search"]."'>"; ?>
	<br><br>
	<?php echo "Tu busqueda: ".$_POST["search"]."<br><br>";?>
	Fecha de salida paquete: <input type="date" name="fecha_inicio"><br><br>
	Fecha de llegada paquete: <input type="date" name="fecha_termino"><br><br>
	Rango de precios: <select name="rango_precios" size="1" id="rango_precios">
	<option value="">Selecciona una opción</option>
    <option value="1">$1 - $500</option>
    <option value="2">$501 - $1000</option>
    <option value="3">$1001 - $1500</option>
    <option value="4">$1501 - $2000</option>
    <option value="5">$2001 - $2500</option>
    <option value="6">$2501 - $3000</option>
    <option value="7">$3001 - Más</option>
	</select><br><br>

	Calificación mínima: <input type="number" min="1" max="5" name="calif_minima" id="calif_minima" style="width:50px;"><br><br>

	Solo Paquetes: <input type="radio" name="solo_paquetes" id="solo_paquetes"><br><br>
	Solo Hoteles: <input type="radio" name="solo_hoteles" id="solo_hoteles"><br><br>
	Ciudad: <input type="text" name="ciudad" id="ciudad"><br><br>
	<input type="submit" name="search_by_genre" value="Filtrar"><br><br>
</form>
</center>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" and (isset($_POST["filtros"])) && ($_POST["filtros"] === "true")) {

	$search = $_POST["search"];
	
	$query_hoteles = "SELECT * FROM hoteles WHERE (nombre LIKE '%$search%' or ciudad LIKE '%$search%') AND ";
	$query_paquetes = "SELECT * FROM paquetes WHERE (nombre LIKE '%$search%' OR ciudad_1 LIKE '%$search%' OR ciudad_2 LIKE '%$search%' OR ciudad_3 LIKE '%$search%' OR fecha_salida LIKE '%$search%' OR fecha_llegada LIKE '%$search%') AND ";

	$fecha_inicio = $_POST['fecha_inicio'] ?? null;
	$fecha_termino = $_POST['fecha_termino'] ?? null;
	$rango_precios = $_POST['rango_precios'] ?? null;
	$calif_minima = $_POST['calif_minima'] ?? null;
	$solo_paquetes = isset($_POST['solo_paquetes']);
	$solo_hoteles = isset($_POST['solo_hoteles']);
	$ciudad = $_POST['ciudad'] ?? null;;

	if (!empty($rango_precios)){
		if ($rango_precios == "1"){
			$precio_max = 500;
			$precio_min = 1;
		}
		elseif ($rango_precios == "2"){
			$precio_max = 1000;
			$precio_min = 501;
		}
		elseif ($rango_precios == "3"){
			$precio_max = 1500;
			$precio_min = 1001;
		}
		elseif ($rango_precios == "4"){
			$precio_max = 2000;
			$precio_min = 1501;
		}
		elseif ($rango_precios == "5"){
			$precio_max = 2500;
			$precio_min = 2001;
		}
		elseif ($rango_precios == "6"){
			$precio_max = 3000;
			$precio_min = 2501;
		}
		elseif ($rango_precios == "7"){
			$precio_min = 3001;
			$precio_max = 9999999999999999999999999999999;
		}
		else{
			$rango_precios = '';
		}
	}
	if ($solo_paquetes){
		if (!empty($fecha_inicio)) {
			$query_paquetes.= "fecha_salida = '".$fecha_inicio."' AND ";
		}
		if (!empty($fecha_termino)){
			$query_paquetes.= "fecha_llegada = '".$fecha_termino."' AND ";
		}
		if (!empty($rango_precios)) {
			$query_paquetes .= "precio_persona >= " . (float)$precio_min . " AND precio_persona <= " . (float)$precio_max . " AND ";
		}
		if (!empty($calif_minima)) {
			$query_paquetes .= "calif_promedio >= '".$calif_minima."' AND ";
		}
		if (!empty($ciudad)) {
			$query_paquetes .= "ciudad_1 LIKE $ciudad OR ciudad_2 LIKE $ciudad OR ciudad_3 LIKE $ciudad AND ";
		}
	}
	elseif ($solo_hoteles){
		if (!empty($rango_precios)) {
			$query_hoteles .= "precio_noche >= " . (float)$precio_min . " AND precio_noche <= " . (float)$precio_max . " AND ";
		}
		if (!empty($calif_minima)) {
			$query_hoteles .= "calif_promedio >= '".$calif_minima."' AND ";
		}
		if (!empty($ciudad)) {
			$query_hoteles.= "LIKE $ciudad AND ";
		}
	}	
	else {
		if (!empty($fecha_inicio)) {
			$query_paquetes.= "fecha_salida = '".$fecha_inicio."' AND ";
			$solo_paquetes = true;
		}
		if (!empty($fecha_termino)){
			$query_paquetes.= "fecha_llegada = '".$fecha_termino."' AND ";
			$solo_paquetes = true;
		}
		if (!empty($rango_precios)) {
			$query_paquetes .= "precio_persona >= " . (float)$precio_min . " AND precio_persona <= " . (float)$precio_max . " AND ";
			$query_hoteles .= "precio_noche >= " . (float)$precio_min . " AND precio_noche <= " . (float)$precio_max . " AND ";
		}
		if (!empty($calif_minima)) {
			$query_hoteles .= "calif_promedio >= '".$calif_minima."' AND ";
			$query_paquetes .= "calif_promedio >= '".$calif_minima."' AND ";
		}
		if (!empty($ciudad)) {
			$query_hoteles .= "ciudad LIKE '%$ciudad%' AND ";
			$query_paquetes .= "ciudad_1 LIKE '%$ciudad%' OR ciudad_2 LIKE '%$ciudad%' OR ciudad_3 LIKE '%$ciudad%' AND ";
		}
	}
	$query_hoteles = substr($query_hoteles, 0, -4);
	$query_paquetes = substr($query_paquetes, 0, -4);

	// Mostrar solo paquetes
	if ($solo_paquetes){
		$consulta_paquetes = mysqli_query($conn,$query_paquetes);
		echo "<div id='container'>";
		while ($row_paquete = mysqli_fetch_assoc($consulta_paquetes)) {
			echo "<div class='col2'>";
			$query = "SELECT * FROM paquetes WHERE id_paquete = '".$row_paquete["id_paquete"]."'";
			$consulta = mysqli_query($conn,$query);
			$rows = mysqli_fetch_assoc($consulta);
			echo "Nombre Paquete: " . $rows["nombre"] . "<br>";
			echo "Precio por persona: $" . $rows["precio_persona"] . "<br>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='ids' value='".$row_paquete["id_paquete"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
		}	
		echo "</div>";
	}
	// Mostrar solo hoteles
	elseif ($solo_hoteles){
		$consulta_hoteles = mysqli_query($conn,$query_hoteles);
		echo "<div id='container'>";
		while ($row_hotel = mysqli_fetch_assoc($consulta_hoteles)){
			echo "<div class='col1'>";
			$query = "SELECT * FROM hoteles WHERE id_hotel = '".$row_hotel["id_hotel"]."'";
			$consulta = mysqli_query($conn,$query);
			$rows = mysqli_fetch_assoc($consulta);
			echo "Nombre Hotel: " . $rows["nombre"] . "<br>";
			echo "Precio por noche: $" . $rows["precio_noche"] . "<br>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='ids' value='".$row_hotel["id_hotel"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
		}
		echo "</div>";
	}
	// Mostrar ambos
	else{
		$consulta_hoteles = mysqli_query($conn,$query_hoteles);
		$consulta_paquetes = mysqli_query($conn,$query_paquetes);
		echo "<div id='container'>";
		while ($row_hotel = mysqli_fetch_assoc($consulta_hoteles)) {
			echo "<div class='col1'>";
			$query = "SELECT * FROM hoteles WHERE id_hotel = '".$row_hotel["id_hotel"]."'";
			$consulta = mysqli_query($conn,$query);
			$rows = mysqli_fetch_assoc($consulta);
			echo "Nombre Hotel: " . $rows["nombre"] . "<br>";
			echo "Precio por noche: $" . $rows["precio_noche"] . "<br>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='ids' value='".$row_hotel["id_hotel"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
		}		
		while ($row_paquete = mysqli_fetch_assoc($consulta_paquetes)) {
			echo "<div class='col2'>";
			$query = "SELECT * FROM paquetes WHERE id_paquete = '".$row_paquete["id_paquete"]."'";
			$consulta = mysqli_query($conn,$query);
			$rows = mysqli_fetch_assoc($consulta);
			echo "Nombre Paquete: " . $rows["nombre"] . "<br>";
			echo "Precio por persona: $" . $rows["precio_persona"] . "<br>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='ids' value='".$row_paquete["id_paquete"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
		}	
		echo "</div>";
	}
}
?>
	
<?php
	if($_SERVER["REQUEST_METHOD"] == "POST" and (isset($_POST["header"])) && ($_POST["header"] === "true")){
		$search = $_POST["search"];

		$query_hoteles = "SELECT * FROM hoteles WHERE nombre LIKE '%$search%' or ciudad LIKE '%$search%'";
		$query_paquetes = "SELECT * FROM paquetes WHERE nombre LIKE '%$search%' or ciudad_1 LIKE '%$search%' or ciudad_2 LIKE '%$search%' or ciudad_3 LIKE '%$search%' or fecha_salida LIKE '%$search%' or fecha_llegada LIKE '%$search%'";

		$consulta_hoteles = mysqli_query($conn, $query_hoteles);
		$consulta_paquetes = mysqli_query($conn, $query_paquetes);
		echo "<div id='container'>";
		while ($row_hotel = mysqli_fetch_assoc($consulta_hoteles)) {
			echo "<div class='col1'>";
			$query = "SELECT * FROM hoteles WHERE id_hotel = '".$row_hotel["id_hotel"]."'";
			$consulta = mysqli_query($conn,$query);
			$rows = mysqli_fetch_assoc($consulta);
			echo "Nombre Hotel: " . $rows["nombre"] . "<br>";
			echo "Precio por noche: $" . $rows["precio_noche"] . "<br>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Hotel'>";
			echo "<input type='hidden' name='ids' value='".$row_hotel["id_hotel"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
		}

		while ($row_paquete = mysqli_fetch_assoc($consulta_paquetes)) {
			echo "<div class='col2'>";
			$query = "SELECT * FROM paquetes WHERE id_paquete = '".$row_paquete["id_paquete"]."'";
			$consulta = mysqli_query($conn,$query);
			$rows = mysqli_fetch_assoc($consulta);
			echo "Nombre Paquete: " . $rows["nombre"] . "<br>";
			echo "Precio por persona: $" . $rows["precio_persona"] . "<br>";
			echo "<form action='store.php' method='post'>";
			echo "<input type='hidden' name='h_p' value='Paquete'>";
			echo "<input type='hidden' name='ids' value='".$row_paquete["id_paquete"]."'>";
			echo "<button type='submit' name='hotel_b' style='border-style:none;width:100px;height:100px'><img src=../imagenes/hotel.jpg width=300 alt='Imagen'></button>";
			echo "</form>";
			echo "</div>";
		}	
		echo "</div>";
	}
?>
</body>