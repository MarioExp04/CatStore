<?php
session_start();


$servername = "127.0.0.1";
$port = "3306";
$username = "root";
$password = "";
$dbname = "CatStore";


$conn = mysqli_connect($servername, $username, $password, $dbname, $port) or die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8");

if (isset($_POST['borrar_carro'])) {
    
    $sql_borrar_carro = "DELETE FROM Carros";
    if (mysqli_query($conn, $sql_borrar_carro)) {
        echo "Carrito borrado exitosamente.";
    } else {
        echo "Error al borrar el carrito: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - CatStore</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
        <h1>Carrito - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Index</a>
        <a href="inicio.php">Iniciar Sesión</a>
        <a href="tienda.php">Productos</a>
        <a href="contacto.php">Contacto</a>
        <a href="crearBD.php">Crear Base de Datos</a>
    </nav>

    <div class="container-carrito">
        <section class="carrito-productos">
            <?php
            
            // Consulta para obtener los productos en el carrito
            $sql_carrito = "SELECT P.Id_Producto, P.Nombre, C.Cantidad, P.Precio FROM Carros C INNER JOIN Productos P ON C.Id_Producto = P.Id_Producto";
            $result_carrito = mysqli_query($conn, $sql_carrito);

            if ($result_carrito && mysqli_num_rows($result_carrito) > 0) {
                $total_carrito = 0;

                echo "<h2>Productos en el Carrito</h2>";

                while ($row = mysqli_fetch_assoc($result_carrito)) {
                    $subtotal = $row['Cantidad'] * $row['Precio'];
                    $total_carrito += $subtotal;

                    echo "<div class='producto-carrito'>";
                    echo "<h3>{$row['Nombre']}</h3>";
                    echo "<p>Precio: {$row['Precio']} €</p>";
                    echo "<p>Cantidad: {$row['Cantidad']}</p>";
                    echo "<p>Subtotal: $subtotal €</p>";
                    echo "</div>";
                }

                echo "<p>Total del Carrito: $total_carrito €</p>";

                echo "<form action='ticket.php' method='post'>";
                echo "<input type='submit' name='comprar' value='Comprar'>";
                echo "</form>";

                echo "<form action='' method='post'>";
                echo "<input type='submit' name='borrar_carro' value='Borrar Carrito'>";
                echo "</form>";
            } else {
                echo "<p>El carrito está vacío</p>";
            }
            ?>
        </section>
    </div>

    <footer>
        &copy; 2024 CatStore
    </footer>

</body>
</html>
