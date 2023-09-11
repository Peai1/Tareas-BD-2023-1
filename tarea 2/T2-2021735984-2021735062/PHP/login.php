<?php
	include_once 'header.php'
?>
	<body>
	<center>
	<br><br><br><br><br><br>
	<img src="../imagenes/picture.jpg" alt="avion" height="200">
	<br>

	<?php
        require 'connection.php';
        session_start();

		$query = "SELECT * FROM usuarios WHERE nombre = '{$_POST['user']}' AND password = '{$_POST['password']}'";
		$consulta = mysqli_query($conn, $query);
		$resultado = mysqli_fetch_assoc($consulta);

		if ($resultado) {
			echo "Bienvenido/a nuevamente, ";
			echo $_POST['user'];
			echo "!";
			echo "<br><br>";

		    $_SESSION["session_user"] = $_POST['user'];
		    $_SESSION["session_password"] = $_POST['password'];

        	$current_user = $_SESSION["session_user"];
		header("Location: index2.php");
		}
		else {
			echo "Usuario incorrecto!";
		}
	?>

    <form action="index.php" method="post">
        <input type="submit" value="Volver">
    </form>

	</center>
	</body>
</html>
