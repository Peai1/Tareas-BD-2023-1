<?php
	include_once 'header2.php';
	require 'connection.php';


	if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["editar"])) && ($_POST["editar"] === "true")){

		$valor_t = $_POST["valor_uni"] * $_POST["cantidad"];

		$query = "UPDATE carrito SET cantidad = '".$_POST["cantidad"]."', pago_total = $valor_t WHERE nro_item = ".$_POST["nro_item"]." AND rut_usuario = '".$_POST["rut_usuario"]."'";
		$consulta = mysqli_query($conn,$query);

		header("Location: carrito2.php");
	}	
	
	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		$query = "SELECT * FROM carrito WHERE nro_item = ".$_POST["nro_item"]." AND rut_usuario = '".$_POST["rut_usuario"]."'";
		$consulta = mysqli_query($conn,$query);
		$row = mysqli_fetch_assoc($consulta);

		if ($row["id_hotel"] != NULL) {
			$query = "SELECT * FROM hoteles WHERE id_hotel = ".$row["id_hotel"]."";
			$consulta2 = mysqli_query($conn, $query);
			$rows = mysqli_fetch_assoc($consulta2);
			echo "<center>";
			echo "<img src='../imagenes/hotel.jpg' width='300' alt='Imagen' style='margin-top: 30px;'>";
			echo "<div class='texto' style='margin-top: 30px;'>";
			echo "<p>Nombre: " . $rows["nombre"] . "</p><br>";
			echo "<p>Precio por noche: $" . (int)$rows["precio_noche"] . "</p><br>";
			echo "<p>Precio total: $" . (int)$row["pago_total"] . " (" . $row["cantidad"] . "x noches)</p><br>";
			echo "<p>Si desea modificar las noches que se quedará en el hotel, ingrese la nueva cantidad:</p><br>";

			echo "<form action='editar_carrito.php' method='post'>";
			echo "<label for='cantidad'>Noches de estadía: </label>";
			echo "<input type='hidden' name='editar' value='true'>";
			echo "<input type='hidden' name='valor_uni' value='" . $rows["precio_noche"] . "'>";
			echo "<input type='hidden' name='nro_item' value='" . $_POST["nro_item"] . "'>";
			echo "<input type='hidden' name='rut_usuario' value='" . $_POST["rut_usuario"] . "'>";
			echo "<select name='cantidad' id='cantidad'>";
			echo "<option value='1'>1</option>";
			echo "<option value='2'>2</option>";
			echo "<option value='3'>3</option>";
			echo "<option value='4'>4</option>";
			echo "<option value='5'>5</option>";
			echo "<option value='6'>6</option>";
			echo "<option value='7'>7</option>";
			echo "</select><br>";
			echo "<button type='submit' name='actualizar'>Actualizar</button><br>";
			echo "<br></form> ";
			echo "</div>";
			echo "</center>";
		}
		else {
			$query = "SELECT * FROM paquetes WHERE id_paquete = ".$row["id_paquete"]."";
			$consulta2 = mysqli_query($conn, $query);
			$rows = mysqli_fetch_assoc($consulta2);
			echo "<center>";
			echo "<img src='../imagenes/hotel.jpg' width='300' alt='Imagen' style='margin-top: 30px;'>";
			echo "<div class='texto' style='margin-top: 30px;'>";
			echo "<p>Nombre: ".$rows["nombre"]."</p><br>";
			echo "<p>Precio por persona: $".(int)$rows["precio_persona"]."</p><br>";
			echo "<p>Precio total: $".(int)$row["pago_total"]." (".$row["cantidad"]."x personas)</p><br>";
			echo "<p>Si desea modificar las cantidad de gente que irá en el paquete, ingrese la nueva cantidad:</p><br>";

			echo "<form action='editar_carrito.php' method='post'>";
			echo "<label for='cantidad'>Cantidad de personas: </label>";
			echo "<input type='hidden' name='editar' value='true'>";
			echo "<input type='hidden' name='valor_uni' value='" . $rows["precio_persona"] . "'>";
			echo "<input type='hidden' name='nro_item' value='" . $_POST["nro_item"] . "'>";
			echo "<input type='hidden' name='rut_usuario' value='" . $_POST["rut_usuario"] . "'>";
			echo "<select name='cantidad' id='cantidad'>";
			for ($i=1; $i<=$rows["maximo_personas"]; $i++) {
				echo "<option value='$i'>$i</option>";
			}
			echo "</select><br>";
			echo "<button type='submit' name='actualizar'>Actualizar</button><br>";
			echo "<br></form> ";
			echo "</div>";
			echo "</center>";
		}
	}
?>

