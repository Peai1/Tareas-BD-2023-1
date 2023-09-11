<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link rel="stylesheet" href="../css/header.css">
	</head>
	<body>
		<header class="enunciado">
			<div class="enunciado-logo">
				<img src="../imagenes/plane.jpg" alt="avion">
				<nav class="enunciado-nav">
					<ul>
						<li><a href="index2.php" style="text-decoration:none;">INICIO</a></li>
						<li><a href="tienda.php" style="text-decoration:none;">TIENDA</a></li>
						<li><a href="wishlist2.php" style="text-decoration:none;">WISHLIST</a></li>
						<li><a href="carrito2.php" style="text-decoration:none;">CARRITO</a></li>
						<?php
							session_start();
							echo "<li>";
							echo "<a href='profile.php' style='text-decoration:none;'>";
							echo $_SESSION["session_user"];
							echo "</a>";
							echo "</li>";
						?>
					</ul>
				</div>
			</div>
			<div class="search">
				<form action="search.php" method="post" class="searchbar">
					<input type="text" name="search" id="live_search" class="form-control">
					<input type='hidden' name='header' value='true'>
					<button type="submit" name="busqueda"><img src="../imagenes/busqueda.png"></button>
				</form>
			</div>
		</header>
	</body>