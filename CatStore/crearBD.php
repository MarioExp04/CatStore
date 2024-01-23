<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear BD</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
        <h1>Base de Datos - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Index</a>
        <a href="inicio.php">Iniciar Sesión</a>
        <a href="tienda.php">Productos</a>
        <a href="carrito.php">Carrito</a>
        <a href="contacto.php">Contacto</a>
    </nav>

    <div class="container-crear" >

        <form action="" method="post">
            <input type="submit" name="create_database" value="Crear Base de Datos">
        </form>

    <?php
    
    $form_submitted = false;

    if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_database"])) {
        
        $form_submitted = true;

        
        $servername = "127.0.0.1";
        $port = "3306";
        $username = "root";
        $password = "";
        $dbname = "CatStore";

        
        $conn = mysqli_connect($servername, $username, $password, '', $port) or die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
        mysqli_set_charset($conn, "utf8");

        // Crear base de datos si no existe
        $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";

        if (mysqli_query($conn, $sql_create_db)) {
            echo "";
        } else {
            echo "Error al crear la base de datos: " . mysqli_error($conn) . "<br>";
        }

        // Seleccionar la base de datos
        mysqli_select_db($conn, $dbname);

        // Borrar tablas existentes
        $borrarTablas = "DROP TABLE IF EXISTS Sesion, Contraseña, Contacto, Carros, Ventas, Cliente, Productos, Cuentas";
        if (mysqli_query($conn, $borrarTablas)) {
            echo "";
        } else {
            echo "Error al borrar tablas: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Cliente
        $sql_cliente = "CREATE TABLE IF NOT EXISTS Cliente (
            DNI VARCHAR(20) PRIMARY KEY,
            Nombre VARCHAR(50) NOT NULL,
            Apellidos VARCHAR(50) NOT NULL,
            Telefono VARCHAR(15) NOT NULL UNIQUE,
            Email VARCHAR(50) NOT NULL UNIQUE,
            NomUser VARCHAR(20) NOT NULL,
            Region VARCHAR(50) NOT NULL,
            Contraseña VARCHAR(50) NOT NULL
        )";
        if (mysqli_query($conn, $sql_cliente)) {
            echo "";
        } else {
            echo "Error al crear tabla Cliente: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Productos
        $sql_productos = "CREATE TABLE IF NOT EXISTS Productos (
            Id_Producto INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(100) NOT NULL,
            Descripcion TEXT NOT NULL,
            Precio DECIMAL(10, 2) NOT NULL,
            Categoria VARCHAR(50) NOT NULL,
            Stock INT,
            Rp INT NOT NULL
        )";
        if (mysqli_query($conn, $sql_productos)) {
            echo "";
        } else {
            echo "Error al crear tabla Productos: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Carros
        $sql_carros = "CREATE TABLE IF NOT EXISTS Carros (
            Id_Producto INT PRIMARY KEY,
            Cantidad INT NOT NULL,
            Precio DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (Id_Producto) REFERENCES Productos(Id_Producto)
        )";


        if (mysqli_query($conn, $sql_carros)) {
            echo "";
        } else {
            echo "Error al crear tabla Carros: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Ventas
        $sql_ventas = "CREATE TABLE IF NOT EXISTS Ventas (
            Id_Producto INT PRIMARY KEY,
            Cantidad INT NOT NULL,
            Precio DECIMAL(10,2) NOT NULL,
            DNI VARCHAR(20) NOT NULL,  -- Nueva columna DNI
            FOREIGN KEY (Id_Producto) REFERENCES Productos(Id_Producto)
        )";

if (mysqli_query($conn, $sql_ventas)) {
    echo "";
} else {
    echo "Error al crear tabla Ventas: " . mysqli_error($conn) . "<br>";
}


        if (mysqli_query($conn, $sql_ventas)) {
            echo "";
        } else {
            echo "Error al crear tabla Ventas: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Cuentas
        $sql_cuentas = "CREATE TABLE IF NOT EXISTS Cuentas (
            Id INT AUTO_INCREMENT PRIMARY KEY,
            Nombre VARCHAR(20),
            Contraseña VARCHAR(50),
            Rp INT
        )";
        if (mysqli_query($conn, $sql_cuentas)) {
            echo "";
        } else {
            echo "Error al crear tabla Cuentas: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Contacto
        $sql_contacto = "CREATE TABLE IF NOT EXISTS Contacto (
            Id INT AUTO_INCREMENT PRIMARY KEY,
            DNI_Cliente VARCHAR(20) NOT NULL,
            Mensaje TEXT NOT NULL,
            Fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
    

        if (mysqli_query($conn, $sql_contacto)) {
            echo "";
        } else {
            echo "Error al crear tabla Contacto: " . mysqli_error($conn) . "<br>";
        }

        // Crear tabla Sesion
        $sql_sesion = "CREATE TABLE IF NOT EXISTS Sesion (
            Dni_Cliente VARCHAR(20) PRIMARY KEY,
            FOREIGN KEY (Dni_Cliente) REFERENCES Cliente(DNI)
        )";
        if (mysqli_query($conn, $sql_sesion)) {
            echo "";
        } else {
            echo "Error al crear tabla Sesion: " . mysqli_error($conn) . "<br>";
        }


        // Ejecutar el archivo SQL para insertar datos en la tabla Productos
        $sql_insert_productos = file_get_contents('productos.sql');
        if (mysqli_multi_query($conn, $sql_insert_productos)) {
            echo "";
        } else {
            echo "Error al insertar datos en la tabla Productos: " . mysqli_error($conn) . "<br>";
        }

        // Ejecutar el archivo SQL para insertar datos en la tabla Cuentas
        $sql_insert_cuentas = file_get_contents('cuentas.sql');
        if (mysqli_multi_query($conn, $sql_insert_cuentas)) {
            echo "";
        } else {
            echo "Error al insertar datos en la tabla Cuentas: " . mysqli_error($conn) . "<br>";
        }

        // Consulta para obtener el stock de cuentas para productos
        $sql_stock_cuentas = "SELECT p.Id_Producto, COUNT(c.Id) AS CantidadCuentas
                             FROM Productos p
                             JOIN Cuentas c ON p.Rp = c.Rp
                             WHERE p.Categoria = 'Cuenta'
                             GROUP BY p.Id_Producto";

        $result_stock_cuentas = mysqli_query($conn, $sql_stock_cuentas);

        if (!$result_stock_cuentas) {
            die("Error al calcular el stock de cuentas: " . mysqli_error($conn));
        }

        // Actualiza el stcok
        while ($row = mysqli_fetch_assoc($result_stock_cuentas)) {
            $id_producto = $row['Id_Producto'];
            $cantidad_cuentas = $row['CantidadCuentas'];

            $sql_update_stock = "UPDATE Productos SET Stock = $cantidad_cuentas WHERE Id_Producto = $id_producto";

            if (!mysqli_query($conn, $sql_update_stock)) {
                die("Error al actualizar el stock: " . mysqli_error($conn));
            }
        }

        mysqli_close($conn);

    }
    ?>

    <?php
    if ($form_submitted) {
        echo "Base de datos creada correctamente";
        $form_submitted = true;
    }
    ?>

    </div>

<footer>
    &copy; 2024 CatStore
</footer>

</body>
</html>