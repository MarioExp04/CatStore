<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - CatStore</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <header>
        <h1>Productos - CatStore</h1>
    </header>

    <nav>
        <a href="index.php">Index</a>
        <a href="inicio.php">Iniciar Sesión</a>
        <a href="carrito.php">Carrito</a>
        <a href="contacto.php">Contacto</a>
        <a href="crearBD.php">Crear Base de Datos</a>
    </nav>

    <div class="categoria-selector">
        <form action="tienda.php" method="get">
            <label for="categoria">Filtrar por categoría:</label>
            <select name="categoria" id="categoria">
                <option value="">Todos</option>
                <option value="Cuenta">Cuenta</option>
                <option value="Skin">Skin</option>
                <option value="Pase">Pase</option>
                <option value="LootBox">LootBox</option>
            </select>
            <input type="submit" value="Filtrar" class="boton-filtrar">
        </form>
    </div>

    <div class="container-productos">
        <?php
        $servername = "127.0.0.1";
        $port = "3306";
        $username = "root";
        $password = "";
        $dbname = "CatStore";

      
        $conn = mysqli_connect($servername, $username, $password, $dbname, $port) or die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
        mysqli_set_charset($conn, "utf8");

        // Filtro por categoría
        $filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : "";
        $filtro_sql = $filtro_categoria ? "WHERE Categoria = '$filtro_categoria'" : "";

        // Consulta para obtener los productos con filtro
        $sql_productos = "SELECT * FROM Productos $filtro_sql";
        $result_productos = mysqli_query($conn, $sql_productos);

        if ($result_productos) {
            while ($row = mysqli_fetch_assoc($result_productos)) {
                echo "<div class='producto'>";
                echo "<h2>{$row['Nombre']}</h2>";
                echo "<p>{$row['Descripcion']}</p>";
                echo "<p>Precio: {$row['Precio']} €</p>";
                echo "<p>Stock: {$row['Stock']}</p>";

                echo "<form action='tienda.php' method='post'>";
                echo "<input type='hidden' name='id_producto' value='{$row['Id_Producto']}'>";
                echo "<input type='hidden' name='nombre' value='{$row['Nombre']}'>";
                echo "<input type='hidden' name='precio' value='{$row['Precio']}'>";

                // Verificar si la cantidad supera el stock
                $max_stock = $row['Stock'];
                echo "<input type='number' name='cantidad' min='1' max='$max_stock' value='1'>";
                
                echo "<button type='submit' name='agregar_carrito' class='boton-agregar-carrito'>Agregar al Carrito</button>";
                echo "</form>";

                echo "</div>";
            }

            if (isset($_POST['agregar_carrito'])) {
                $id_producto = $_POST['id_producto'];
                $nombre = $_POST['nombre'];
                $precio = $_POST['precio'];
                $cantidad = $_POST['cantidad'];

                // Obtener el stock
                $sql_stock = "SELECT Stock FROM Productos WHERE Id_Producto = '$id_producto'";
                $result_stock = mysqli_query($conn, $sql_stock);

                if ($result_stock && mysqli_num_rows($result_stock) > 0) {
                    $row_stock = mysqli_fetch_assoc($result_stock);
                    $stock_disponible = $row_stock['Stock'];

                    // Verificar si la cantidad supera el stock
                    if ($cantidad <= $stock_disponible) {

                        // Verificar si el producto ya existe en Carros
                        $sql_verificar_producto = "SELECT * FROM Carros WHERE Id_Producto = '$id_producto'";
                        $result_verificar_producto = mysqli_query($conn, $sql_verificar_producto);

                        if ($result_verificar_producto) {
                            if (mysqli_num_rows($result_verificar_producto) > 0) {

                                // El producto ya existe, actualizar la cantidad

                                $sql_actualizar_cantidad = "UPDATE Carros SET Cantidad = Cantidad + 1 WHERE Id_Producto = '$id_producto'";
                                if (mysqli_query($conn, $sql_actualizar_cantidad)) {
                                    echo "Cantidad actualizada en el carrito.";
                                } else {
                                    echo "Error al actualizar la cantidad: " . mysqli_error($conn);
                                }
                            } else {

                                // El producto no existe, insertar nuevo registro
                                $sql_insertar_carrito = "INSERT INTO Carros (Id_Producto, Cantidad, Precio) VALUES ('$id_producto', 1, '$precio')";
                                if (mysqli_query($conn, $sql_insertar_carrito)) {
                                    echo "Producto agregado al carrito exitosamente.";
                                } else {
                                    echo "Error al agregar el producto al carrito: " . mysqli_error($conn);
                                }
                            }
                        } else {
                            echo "Error al verificar el producto en el carrito: " . mysqli_error($conn);
                        }
                    } else {
                        echo "La cantidad seleccionada supera el stock disponible.";
                    }
                } else {
                    echo "Error al obtener el stock del producto: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Error al obtener los productos: " . mysqli_error($conn);
        }

       
        mysqli_close($conn);
        ?>
    </div>

    <footer>
        &copy; 2024 CatStore
    </footer>

</body>
</html>

