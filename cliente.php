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


$search = isset($_GET['search']) ? $_GET['search'] : '';
$gen = isset($_GET['gene']) ? $_GET['gene'] : '';      //obtenemos lo que hay en el formulario de busqueda
$clasi = isset($_GET['clas']) ? $_GET['clas'] : '';


$where = []; // Creamos un array...

if ($search) { // Si se busca por palabra...
    $where[] = "(nom_v LIKE '%$search%' OR desc_v LIKE '%$search%')"; // Guardamos en el array where con su debida consulta
}

if ($gen) { // Si se busca por género
    $where[] = "genero_v = '$gen'"; // Guardamos en el array where cuando genero_v = 'loquehayabuscadoelusuario'
}

if ($clasi) {
    $where[] = "clasif_v = '$clasi'";
}

$vwhere = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

$consulta_count = "SELECT COUNT(*) as ct FROM videojuegos $vwhere";
$result_count = $conn->query($consulta_count);
$total = mysqli_fetch_assoc($result_count)['ct'];

// Total de páginas necesarias
$paginas = ceil($total / $limit);

if ($search) {
    //si hay busqueda no aplicamos un cierto limite de productos
    $consulta = "SELECT id_v, nom_v, desc_v, fecha_lanz, clasif_v, genero_v, precio, imagen FROM videojuegos $vwhere";
} else {
    //sino hay búsqueda aplicamos un limite de ´productos
    $consulta = "SELECT id_v, nom_v, desc_v, fecha_lanz, clasif_v, genero_v, precio, imagen FROM videojuegos $vwhere LIMIT $inicio, $limit";
}

$result = $conn->query($consulta);

$generos = $conn->query("SELECT genero_v FROM videojuegos GROUP BY genero_v")->fetch_all(MYSQLI_ASSOC); // Obtenemos los géneros de la base de datos 
$clasi = $conn->query("SELECT clasif_v FROM videojuegos GROUP BY clasif_v")->fetch_all(MYSQLI_ASSOC);  // Obtenemos las clasificaciones de la base de datos 



$generos = $conn->query("SELECT genero_v FROM videojuegos GROUP BY genero_v")->fetch_all(MYSQLI_ASSOC); //obtenemos los generos de la base de datos 
$clasi = $conn->query("SELECT clasif_v FROM videojuegos GROUP BY clasif_v")->fetch_all(MYSQLI_ASSOC);  //obtenemos las clasificaciones de la base de datos 

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
            font-family: "Roboto", sans-serif;
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Doto:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Doto:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Port+Lligat+Sans&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="cart-icon" onclick="toggleCart()">
            <img src="carrito.jpg" alt=""> <span id="cart-count">0</span>
        </div>
        <form method="GET">
            <input type="text" name="search" placeholder="Buscar" value="<?php echo htmlspecialchars($search); ?>">
            <select name="gene">
                <option value="">Selecciona Género</option>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?php echo htmlspecialchars($genero['genero_v']); ?>" <?php echo $gen == $genero['genero_v'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($genero['genero_v']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="clas">
                <option value="">Selecciona Clasificación</option>
                <?php foreach ($clasi as $clas): ?>
                    <option value="<?php echo htmlspecialchars($clas['clasif_v']); ?>" <?php echo $clasi == $clas['clasif_v'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($clas['clasif_v']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Buscar">
        </form>

        <a href="cliente.php" style="all: unset;">
            <h1 style="cursor: pointer; font-family: 'Doto', sans-serif; font-size: xxx-large;" >BLACK-GAMES</h1>
        </a>
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
                    <p><strong>Género:</strong> <?php echo htmlspecialchars($product['genero_v']); ?></p>
                    <p><strong>Clasificación:</strong> <?php echo htmlspecialchars($product['clasif_v']); ?></p>

                    <!-- Formulario para agregar al carrito -->
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id_v']; ?>">
                        <button type="submit" name="add_to_cart">Agregar al Carrito</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if (!$search): //si no hay busqueda mostar paginacion ?>
            <div class="pagination"
                style="display: flex; align-items: center; flex-direction: row; justify-content: center;">
                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                    <a href="?pagina=<?php echo $i; ?>"><button
                            class="<?php echo $i == $pagina_actual ? 'active' : ''; ?>"><?php echo $i; ?></button></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
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