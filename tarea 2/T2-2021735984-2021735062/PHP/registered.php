<?php
	include_once 'header.php'
?>
    <body>
    <center>
    <br><br><br><br><br><br>
    <img src="../imagenes/picture.jpg" alt="foto" height="200"> 
    <p>
    Bienvenido a PrestigeTravels
    </p>
    <br>
    <?php
        // Registra un nuevo Usuario a la Base de Datos
        require 'connection.php';
        session_start();
        // Pide los datos del Usuario a través de la página
        $register_rut = $_POST["rut"];
        $register_name = $_POST["nombre"];
        $register_email = $_POST["email"];
        $register_password = $_POST["password"];
        $register_fecha_nac = $_POST["fecha_nac"];

        if ($register_name == "") {
            echo "<br>Ingrese un Usuario valido!<br><br>";
        }
        else {
            // Realiza una Query para insertar al Usuario
            $query = "SELECT * FROM usuarios WHERE rut = $register_rut";
            $consulta = mysqli_query($conn, $query);

            if (mysqli_num_rows($consulta) > 0) {
                echo "El rut ya está ingresado. Por favor, ingrese un rut diferente.";} 
            else {
                $query = "INSERT INTO usuarios (rut, nombre, fecha_nacimiento, correo, password) VALUES('$register_rut','$register_name','$register_fecha_nac','$register_email', '$register_password')";            
                $consulta = mysqli_query($conn, $query);
                if($consulta){
                    echo "Se creó el Usuario exitosamente!";
                }
                else{
                    echo mysqli_error($cn);
                }
            }
        }
    ?>
    <br><br>
    <form action="index.php" method="post">
        Volver a la página principal
        <br>
        <input type="submit" value="Volver">
    </form>

    </center>
    </body>
</html>