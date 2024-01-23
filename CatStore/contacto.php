<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactar</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
        <h1>Contacto - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Index</a>
        <a href="inicio.php">Iniciar Sesión</a>
        <a href="tienda.php">Productos</a>
        <a href="carrito.php">Carrito</a>
        <a href="crearBD.php">Crear Base de Datos</a>
    </nav>

    <div class="container-contacto" >

        <form action="" method="post">
            <label for="dni" class="contacto-label">DNI:</label>
            <input type="text" name="dni" pattern="[0-9]{8}[A-Za-z]" title="Formato válido: 8 números y una letra" placeholder="Introduce tu DNI" required maxlength="9">
            <br>
            <label for="mensaje" class="contacto-label">Mensaje:</label>
            <textarea name="mensaje" rows="4" placeholder="Introduce tu mensaje" required></textarea>
            <br>
            <input type="submit" name="enviar_mensaje" value="Enviar mensaje">
        </form>

        <?php
    
        $servername = "127.0.0.1";
        $port = "3306";
        $username = "root";
        $password = "";
        $dbname = "CatStore";

        $conn = mysqli_connect($servername . ":" . $port, $username, $password, $dbname);


        if (!$conn) {
            die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
        }

        // Verificar si se ha enviado el formulario para proceder al envío
        if (isset($_POST['enviar_mensaje'])) {

            
            $dni = mysqli_real_escape_string($conn, $_POST['dni']);
            $mensaje = mysqli_real_escape_string($conn, $_POST['mensaje']);

            // Insertar los datos en la tabla "Contacto"
            $sql_insert = "INSERT INTO Contacto (DNI_Cliente, Mensaje) VALUES ('$dni', '$mensaje')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "Enviando mensaje...";
                
                // Redirige a la pagina tras 3 segundos
                header("refresh:3;url=mensaje.html");
                exit(); 
            } else {
                echo "Error al enviar el mensaje: " . mysqli_error($conn);
            }
        }

        
        mysqli_close($conn);
        ?>

    </div>

    <footer>
        &copy; 2024 CatStore
    </footer>

</body>
</html>
