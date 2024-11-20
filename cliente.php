<?php
session_start();

include('php/conec.php');
$conn = $conexion;

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;

// egistro de inicio para la consulta
$inicio = ($pagina_actual - 1) * $limit;

//total de productos
$consulta_count = "SELECT COUNT(*) as ct FROM videojuegos";
$result_count = $conn->query($consulta_count);
$total = mysqli_fetch_assoc($result_count)['ct'];
$paginas = ceil($total / $limit); // Total de páginas necesarias

// pproductos para la página actual
$search = isset($_GET['search']) ? $_GET['search'] : '';



if ($search) { //si hay $search
    $consulta_count = "SELECT COUNT(*) as ct FROM videojuegos WHERE nom_v LIKE '%$search%' OR desc_v LIKE '%$search%'"; //buscar por....
    $consulta = "SELECT id_v, nom_v, desc_v, fecha_lanz, clasif_v, genero_v, precio, imagen FROM videojuegos WHERE nom_v LIKE '%$search%' OR desc_v LIKE '%$search%' LIMIT $inicio, $limit";
} else { // sino, consulta todos los videojuegos
    $consulta_count = "SELECT COUNT(*) as ct FROM videojuegos";
    $consulta = "SELECT id_v, nom_v, desc_v, fecha_lanz, clasif_v, genero_v, precio, imagen FROM videojuegos LIMIT $inicio, $limit";
}
$result = $conn->query($consulta);



//agg productos al carrito
if (isset($_POST['add_to_cart'])) {
    $id_producto = $_POST['product_id'];

    //no agregar dos veces el mismo juego al arro
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // si no esta el juego agregarlo
    if (!isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] = 1;
    }
}

// Eliminar producto  del carr
if (isset($_POST['remove_from_cart'])) {
    $id_producto = $_POST['product_id'];
    unset($_SESSION['carrito'][$id_producto]);
}

//carito vaciar
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['carrito']);
}

//número de productos en el carrito
function getCarritoCount()
{
    return isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE BLACK-GAMES</title>
    <link rel="stylesheet" href="css/num_pg.css">
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #000000;
            color: white;
            padding: 10px 20px;
        }

        .cart-icon {
            cursor: pointer;
            position: relative;
        }

        #cart-count {
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -5px;
            right: -10px;
        }

        main {
            padding: 20px;
        }

        #product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .product {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        button {
            background: #050047;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .pagination button {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #050047;
            color: white;
            border: none;
            cursor: pointer;
        }

        .pagination button.active {
            font-weight: bold;
        }

        #cart-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: white;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        #cart-sidebar.visible {
            transform: translateX(0);
        }

        #cart-sidebar.hidden {
            transform: translateX(100%);
        }

        #cart-items li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <header>
        <div class="cart-icon" onclick="toggleCart()">
            <img src="carrito.jpg" alt=""> <span id="cart-count">0</span>
        </div>
        <form method="GET">
            <input type="text" name="search" placeholder="Buscar"
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <input type="submit" value="Buscar">
        </form>
        <h1>BLACK-GAMES</h1>
    </header>

    <main>
        <div id="product-list">
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <div class="product">

                    <img src="<?php echo htmlspecialchars($product['imagen']); ?>"
                        alt="<?php echo htmlspecialchars($product['nom_v']); ?>"
                        style="width: 150px; height: auto; margin-bottom: 10px;">

                    <h3><?php echo htmlspecialchars($product['nom_v']); ?></h3>

                    <p>Descripción: <?php echo htmlspecialchars($product['desc_v']); ?></p>
                    <p><strong>Precio:</strong> $<?php echo number_format($product['precio'], 2); ?></p>

                    <!-- Formulario para agregar al carrito -->
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id_v']; ?>">
                        <button type="submit" name="add_to_cart">Agregar al Carrito</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        <!-- Paginacion de new -->
        <div class="pagination"
            style=" display: flex; align-items: center; flex-direction: row; justify-content: center; ">
            <?php for ($i = 1; $i <= $paginas; $i++): ?>
                <a href="?pagina=<?php echo $i; ?>"><button
                        class="<?php echo $i == $pagina_actual ? 'active' : ''; ?>"><?php echo $i; ?></button></a>
            <?php endfor; ?>
        </div>
    </main>

    <!-- Carrooooooooo -->
    <aside id="cart-sidebar" class="hidden">
        <h2>Tu Carrito</h2>
        <ul id="cart-items">
            <?php
            $subtotal_total = 0;
            $iva_total = 0;
            $id_productos = [];  //almacena los ID de los productos
            if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                <?php foreach ($_SESSION['carrito'] as $id => $quantity): ?>
                    <?php
                    //detalles del producto
                    $consulta = "SELECT id_v, nom_v, precio FROM videojuegos WHERE id_v = $id";
                    $result = $conn->query($consulta);
                    $producto = $result->fetch_assoc();

                    //subtotal e IVA
                    $subtotal = $producto['precio'] * $quantity;
                    $iva = $subtotal * 0.16;

                    $subtotal_total += $subtotal;
                    $iva_total += $iva;
                    $id_productos[] = $id;
                    ?>
                    <li>
                        <span><?php echo htmlspecialchars($producto['nom_v']); ?></span>
                        <span>Precio: $<?php echo number_format($producto['precio'], 2); ?></span>
                        <span>Cantidad: <?php echo $quantity; ?></span>
                        <span>Subtotal: $<?php echo number_format($subtotal, 2); ?></span>
                        <span>IVA: $<?php echo number_format($iva, 2); ?></span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <button type="submit" name="remove_from_cart">Quitar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Tu carrito está vacío.</li>
            <?php endif; ?>
        </ul>
        <div>
            <h3>Resumen de la Compra</h3>
            <p><strong>Subtotal:</strong> $<?php echo number_format($subtotal_total, 2); ?></p>
            <p><strong>IVA (16%):</strong> $<?php echo number_format($iva_total, 2); ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($subtotal_total + $iva_total, 2); ?></p>
        </div>
        <form method="POST">
            <button type="submit" name="clear_cart">Vaciar carrito</button>
        </form>
        <a href="tarjeta.php"><button>Comprar</button></a>
    </aside>








    <script>
        function toggleCart() {
            const cartSidebar = document.getElementById("cart-sidebar");
            cartSidebar.classList.toggle("visible");
            cartSidebar.classList.toggle("hidden");
        }
    </script>

</body>

</html>

<?php
$conn->close(); // Cerrarconexión con gb
?>