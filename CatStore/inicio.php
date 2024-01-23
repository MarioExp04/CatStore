<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body class="page-inicio">

    <header>
        <h1>Iniciar sesión - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="tienda.php">Productos</a>
        <a href="carrito.php">Carrito</a>
        <a href="contacto.php">Contacto</a>
        <a href="crearBD.php">Crear Base de Datos</a>
    </nav>

    <div class="container-login-inicio">
        <form action="#" method="post">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" pattern="[0-9]{8}[A-Za-z]" title="Formato válido: 8 números y una letra" placeholder="Introduce tu DNI" required maxlength="9">

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Introduce tu Contraseña" required>

            <input type="submit" value="Iniciar sesión" name="iniciar_sesion">
        </form>

        <form action="#" method="post">
            <input type="submit" value="Cerrar sesión" class="cerrar-sesion-btn" name="cerrar_sesion">
        </form>

        <p>No tienes cuenta? <a href="registrarse.php">Regístrate</a></p>
    </div>

    <?php
    
    $servername = "127.0.0.1";
    $port = "3306";
    $username = "root";
    $password = "";
    $dbname = "CatStore";

    
    if (isset($_POST["iniciar_sesion"])) {
        

       
        $conn = mysqli_connect($servername, $username, $password, $dbname, $port) or die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
        mysqli_set_charset($conn, "utf8");

        // Verificar si ya hay un DNI en la tabla Sesion
        $sql_verificar_sesion = "SELECT * FROM Sesion";
        $result_verificar_sesion = mysqli_query($conn, $sql_verificar_sesion);

        if (mysqli_num_rows($result_verificar_sesion) > 0) {
            echo '<p class="error sesion-activa">Ya hay una sesión activa. Cierre sesión antes de iniciar otra.</p>';
        } else {
            // Verificar la existencia del cliente
            $dni = $_POST["dni"];
            $contrasena = $_POST["password"];
            $sql_verificar_cliente = "SELECT * FROM Cliente WHERE DNI = '$dni' AND Contraseña = '$contrasena'";
            $result_verificar_cliente = mysqli_query($conn, $sql_verificar_cliente);

            if (mysqli_num_rows($result_verificar_cliente) > 0) {
                // Insertar el DNI en la tabla Sesion
                $sql_insertar_sesion = "INSERT INTO Sesion (Dni_Cliente) VALUES ('$dni')";
                if (mysqli_query($conn, $sql_insertar_sesion)) {
                    echo '<p class="success iniciar-sesion">Sesión iniciada correctamente</p>';
                } else {
                    echo '<p class="error iniciar-sesion">Error al iniciar sesión: ' . mysqli_error($conn) . '</p>';
                }
            } else {
                echo '<p class="error dni-incorrecto">DNI o contraseña incorrectos</p>';
            }
        }

        
        mysqli_close($conn);
    }

    
    if (isset($_POST["cerrar_sesion"])) {
        

        
        $conn = mysqli_connect($servername, $username, $password, $dbname, $port) or die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
        mysqli_set_charset($conn, "utf8");

        // Borrar la sesion iniciadoa
        $sql_borrar_sesion = "DELETE FROM Sesion";

        if (mysqli_query($conn, $sql_borrar_sesion)) {
            echo '<p class="success cerrar-sesion">Sesión cerrada correctamente</p>';
        } else {
            echo '<p class="error cerrar-sesion">Error al cerrar sesión: ' . mysqli_error($conn) . '</p>';
        }

       
        mysqli_close($conn);
    }
    ?>

    <footer>
        &copy; 2024 CatStore
    </footer>

</body>
</html>
