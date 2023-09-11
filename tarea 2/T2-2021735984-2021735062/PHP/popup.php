<html>
<body>

<link rel="stylesheet" href="../css/popup.css">
	
	<div class="container">
		<button type="submit" class="btn" id="btn2" onclick="openPopup()"></button>
		<div class="popup" id="popup">
			<img src="../imagenes/oferta.png">
			<h1>¡Ganaste un descuento de un 10%!</h1>
			<p>Si lo aceptas, se verá reflejado en el total de tu carrito</p>
			<?php
			echo "<form action='aplicar_descuento.php' method='post' class='form_aceptar'>";
			echo "<button type='submit' class='aceptar'>Aceptar</button>";
			echo "</form>";
			?>
			<button type"button" class="rechazar" onclick="closePopup()">Rechazar</button
		</div>
	</div>


<script>
let popup = document.getElementById("popup");

function openPopup() {
	popup.classList.add("open-popup");
}

function closePopup() {
	popup.classList.remove("open-popup");
}

if ((Math.floor(Math.random() * 10)) == 0) {
	document.getElementById('btn2').click();
}

</script>

</body>
</html>