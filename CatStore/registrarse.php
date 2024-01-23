<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - CatStore</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles_registro.css">
    <link rel="stylesheet" type="text/css" href="registro.css">
</head>
<body class="page-registrarse">

    <header>
        <h1>Registro de Usuario - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="tienda.php">Productos</a>
        <a href="carrito.php">Carrito</a>
        <a href="contacto.php">Contacto</a>
        <a href="crearBD.php">Crear Base de Datos</a>
    </nav>

    <div class="container-registrarse">
        <form action="" method="post" onsubmit="return validateForm()">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" pattern="[0-9]{8}[A-Za-z]" title="Formato válido: 8 números y una letra" placeholder="Introduce tu DNI" required maxlength="9">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Introduce tu Nombre" required>
            
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" placeholder="Introduce tus Apellidos" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" pattern="[0-9]{9}" title="Formato válido: 9 números" placeholder="Introduce tu Teléfono" required maxlength="9">

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Introduce tu Correo electrónico" required>

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" placeholder="Introduce tu Usuario" required>

            <label for="region">Región:</label>
            <select id="region" name="region" required>
                <option value="euw">EUW</option>
                <option value="lanlas">LAN/LAS</option>
                <option value="oce">OCE</option>
            </select>

            <label for="contrasena">Contraseña:</label>
            <div class="contrasena-container">
                <input type="password" id="contrasena" name="contrasena" placeholder="Introduce tu Contraseña" required>
                <span class="toggle-icon" onclick="togglePassword('contrasena')">&#128065;</span>
            </div>

            <label for="repetir_contrasena">Repetir Contraseña:</label>
            <div class="contrasena-container">
                <input type="password" id="repetir_contrasena" name="repetir_contrasena" placeholder="Repite tu Contraseña" required>
                <span class="toggle-icon" onclick="togglePassword('repetir_contrasena')">&#128065;</span>
            </div>

            
            <div id="error-message" style="color: red;"></div>

            <input type="submit" name="registrarse" value="Registrarse">
        </form>

        <?php
        $servername = "127.0.0.1";
        $port = "3306";
        $username = "root";
        $password = "";
        $dbname = "CatStore";

        $conn = mysqli_connect($servername . ":" . $port, $username, $password, $dbname);
        mysqli_set_charset($conn, "utf8");

        if (!$conn) {
            die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
        }

        if (isset($_POST['registrarse'])) {
            $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);
            $repetir_contrasena = mysqli_real_escape_string($conn, $_POST['repetir_contrasena']);

            // Verificar si las contraseñas coinciden
            if ($contrasena !== $repetir_contrasena) {
                echo "<div class='mensaje-error'>Las contraseñas no coinciden.</div>";
            } else {
                // Inserccion en la tabla cliente de tus datos u.u
                $dni = mysqli_real_escape_string($conn, $_POST['dni']);
                $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
                $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
                $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
                $region = mysqli_real_escape_string($conn, $_POST['region']);

                // Verificar si el DNI ya existe
                $sql_check_dni = "SELECT DNI FROM Cliente WHERE DNI = '$dni'";
                $result_check_dni = mysqli_query($conn, $sql_check_dni);

                if (mysqli_num_rows($result_check_dni) > 0) {
                    echo "<div class='mensaje-error'>Error al registrar el usuario: DNI ya utilizado.</div>";
                    mysqli_close($conn);
                    exit();
                }

                // Verificar si el teléfono ya existe
                $sql_check_telefono = "SELECT Telefono FROM Cliente WHERE Telefono = '$telefono'";
                $result_check_telefono = mysqli_query($conn, $sql_check_telefono);

                if (mysqli_num_rows($result_check_telefono) > 0) {
                    echo "<div class='mensaje-error'>Error al registrar el usuario: Teléfono ya utilizado.</div>";
                    mysqli_close($conn);
                    exit();
                }

                // Verificar si el correo ya existe
                $sql_check_correo = "SELECT Email FROM Cliente WHERE Email = '$email'";
                $result_check_correo = mysqli_query($conn, $sql_check_correo);

                if (mysqli_num_rows($result_check_correo) > 0) {
                    echo "<div class='mensaje-error'>Error al registrar el usuario: Correo electrónico ya utilizado.</div>";
                    mysqli_close($conn);
                    exit();
                }

                // Si todas las verificaciones son exitosas, continua
                $sql_insert = "INSERT INTO Cliente (DNI, Nombre, Apellidos, Telefono, Email, NomUser, Region, Contraseña) 
                            VALUES ('$dni', '$nombre', '$apellidos', '$telefono', '$email', '$usuario', '$region', '$contrasena')";

                if (mysqli_query($conn, $sql_insert)) {
                    echo "<div class='mensaje-exito'>Registro exitoso. Redirigiendo a Inicio...</div>";
                    echo "<div id='container-mensaje'></div>";

                    // Redirigir a los 3 segundos
                    echo "<script>";
                    echo "setTimeout(function () {";
                    echo "  window.location.href = 'registro.html';";
                    echo "}, 3000);";
                    echo "</script>";

                    exit();
                } else {
                    echo "<div class='mensaje-error'>Error al registrar el usuario: " . mysqli_error($conn) . "</div>";
                }
            }
        }

        mysqli_close($conn);
        ?>




        <p>¿Ya tienes cuenta? <a href="inicio.php">Inicia sesión</a></p>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '&#128064;';
            } else {
                input.type = 'password';
                icon.innerHTML = '&#128065;';
            }
        }

        function validateForm() {
            
            document.getElementById('error-message').innerHTML = '';
            return true;  
        }
    </script>
</body>
</html>
