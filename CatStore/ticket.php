<?php
session_start();


$servername = "127.0.0.1";
$port = "3306";
$username = "root";
$password = "";
$dbname = "CatStore";


$conn = mysqli_connect($servername, $username, $password, $dbname, $port) or die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8");

if (isset($_POST['comprar'])) {
    
    $dni = isset($_POST['dni']) ? $_POST['dni'] : "";

    
    $sql_carrito = "SELECT P.Id_Producto, C.Cantidad, P.Precio FROM Carros C INNER JOIN Productos P ON C.Id_Producto = P.Id_Producto";
    $result_carrito = mysqli_query($conn, $sql_carrito);

    if ($result_carrito && mysqli_num_rows($result_carrito) > 0) {
        
        while ($row = mysqli_fetch_assoc($result_carrito)) {
            $id_producto = $row['Id_Producto'];
            $cantidad = $row['Cantidad'];
            $precio = $row['Precio'];

            $sql_almacenar_venta = "INSERT INTO Ventas (Id_Producto, Cantidad, Precio, DNI) VALUES ('$id_producto', '$cantidad', '$precio', '$dni')";
            if (!mysqli_query($conn, $sql_almacenar_venta)) {
                echo "Error al almacenar la venta: " . mysqli_error($conn);
                exit;
            }
        }

        
        $sql_borrar_carro = "DELETE FROM Carros";
        if (!mysqli_query($conn, $sql_borrar_carro)) {
            echo "Error al borrar el carrito: " . mysqli_error($conn);
            exit;
        }

        
        header("Location: venta.html");
        exit;
    } else {
        echo "No hay productos en el carrito.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - CatStore</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
        <h1>Ticket - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Index</a>
        <a href="inicio.php">Iniciar Sesión</a>
        <a href="tienda.php">Productos</a>
        <a href="contacto.php">Contacto</a>
        <a href="crearBD.php">Crear Base de Datos</a>
    </nav>

    <div class="container-ticket">
        <section class="info-venta">
            <?php
            
            echo "<h2>Resumen de la Compra</h2>";

            
            $sql_venta = "SELECT V.Id_Producto, P.Nombre, V.Cantidad, V.Precio FROM Ventas V INNER JOIN Productos P ON V.Id_Producto = P.Id_Producto WHERE V.DNI = '$dni'";
            $result_venta = mysqli_query($conn, $sql_venta);

            if ($result_venta && mysqli_num_rows($result_venta) > 0) {
                $total_venta = 0;

                while ($row = mysqli_fetch_assoc($result_venta)) {
                    $subtotal = $row['Cantidad'] * $row['Precio'];
                    $total_venta += $subtotal;

                    echo "<div class='producto-venta'>";
                    echo "<h3>{$row['Nombre']}</h3>";
                    echo "<p>Precio: {$row['Precio']} €</p>";
                    echo "<p>Cantidad: {$row['Cantidad']}</p>";
                    echo "<p>Subtotal: $subtotal €</p>";
                    echo "</div>";
                }

                echo "<p>Total de la Venta: $total_venta €</p>";
            } else {
                echo "<p>No hay productos en la venta.</p>";
            }
            ?>
        </section>

        <section class="formulario-dni">
            <h2>Introduce tu DNI</h2>
            <form action="" method="post">
                <label for="dni">DNI:</label>
                <input type="text" name="dni" id="dni" required>
                <input type="submit" name="comprar" value="Comprar">
            </form>
        </section>
    </div>

    <footer>
        &copy; 2024 CatStore
    </footer>

</body>
</html>
