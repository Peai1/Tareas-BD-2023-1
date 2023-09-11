<?php
	include_once 'header.php'
?>
	<body>
	<center>
	<br>
	<form action="login.php" method="post">
		Iniciar Sesión
		<br><br>
        Usuario: <input type="text" name="user">
        <br>
        Contraseña: <span style="margin-right: 21px;"><input type="password" name="password"></span>
        <br><br>
		<input type="submit" value="Ingresar">
	</form>

	<form action="register.php" method="post">
		¿No tiene una cuenta? Registrese aquí
		<br><br>
		<input type="submit" value="Registrarse">
	</form>
	</center>
	</body>
</html>
