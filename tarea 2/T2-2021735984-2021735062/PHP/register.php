<?php
	include_once 'header.php'
?>
	<body>
	<center>
	<br>
    Registre sus datos a continuación!
	<br><br>
	<form action="registered.php" method="post">
		Rut: <input type="text" placeholder="Sin puntos ni guión" name = "rut">
		<br>
		Nombre: <span style="margin-right: 29px;"><input type="text" name="nombre"></span>
		<br>
		E-Mail: <span style="margin-right: 22px;"><input type="text" name="email"></span>
		<br>
		Contraseña: <span style="margin-right: 49px;"><input type="text" name="password"></span>
		<br>
		Fecha de nacimiento: <span style="margin-right: 142px;"><input type="date" name = "fecha_nac"></span>
		<br><br>
		<input type="submit" value ="Registrarse">
	</form>

    <form action="index.php" method="post">
        Volver a la página principal
        <br>
        <input type="submit" value="Volver">
    </form>

	<center>
	</body>
</html>
