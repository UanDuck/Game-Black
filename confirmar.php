<?php
session_start();
include('php/conec.php');
$conn = $conexion;

function obtenerDatos($conexion, $sql, $parametros = [])
{ //creamos la funcion obtenerdatos porque la vamos a volver a usar
    $stmt = mysqli_prepare($conexion, $sql);
    if ($parametros) {
        // Asumiendo que todos los parámetros son de tipo string
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($parametros)), ...$parametros);
    }
    mysqli_stmt_execute($stmt);
    $result_cons = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_cons, MYSQLI_ASSOC);
}

$id_u = $_SESSION['id_u'];

// Verificar si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: cliente.php'); // Redirigir si no hay productos en el carrito
    exit;
}


$tarjetas = obtenerDatos($conn, "SELECT * FROM tarjeta WHERE id_u = ?", [$id_u]);
//checamos sii el usuario tiene tarjetas
if (empty($tarjetas)) {
    $_SESSION['error'] = 'Error, no hay tarjetas registradas: ' . mysqli_error($conexion);
    header('Location: tarjeta.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_tarjeta = $_POST['tarjeta']; //guardamos el id de la th selec
    $tarjeta_seleccionada = obtenerDatos($conn, "SELECT * FROM tarjeta WHERE id_tj = ?", [$id_tarjeta]); //obtenemos los datos de esa tj
    if ($tarjeta_seleccionada) {
        $tarjeta = $tarjeta_seleccionada[0]; // Tomar el primer resultado
        $num_tarjeta_usada = htmlspecialchars($tarjeta['num_tj']);
    } else {
        $num_tarjeta_usada = 'No disponible';
    }
} else {
    $num_tarjeta_usada = 'No disponible';
}



// Calcular el subtotal, IVA y total
$subtotal_total = 0;
$iva_total = 0;
$productos = []; //almacenar los productos del carrito
foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
    // Obtener detalles del producto
    $consulta = "SELECT id_v, nom_v, precio, imagen FROM videojuegos WHERE id_v = ?";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    // Calcular el subtotal e IVA
    $subtotal = $producto['precio'] * $cantidad;
    $iva = $subtotal * 0.16;

    // Acumular los totales
    $subtotal_total += $subtotal;
    $iva_total += $iva;

    //detalles del producto
    $productos[] = [
        'id' => $producto['id_v'],
        'nombre' => $producto['nom_v'],
        'precio' => $producto['precio'],
        'imagen' => $producto['imagen'],
        'subtotal' => $subtotal,
        'iva' => $iva
    ];
}

// totaala
$total = $subtotal_total + $iva_total;

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_u'])) {
    header('Location: login.php'); // Redirigir a login si no está logueado
    exit;
}

$id_u = $_SESSION['id_u']; // ID del usuario

// Registrar la compra 
$fecha_compra = date('Y-m-d H:i:s');
$sql_compra = "INSERT INTO compra (total, subtotal, id_u) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_compra);
$stmt->bind_param("ddi", $total, $subtotal_total, $id_u);
$stmt->execute();
$id_c = $stmt->insert_id; // Obtener el ID de la compra recién insertada

// Registrar vc
foreach ($productos as $producto) {
    $sql_vc = "INSERT INTO vc (id_v, id_c) VALUES (?, ?)";
    $stmt_vc = $conn->prepare($sql_vc);
    $stmt_vc->bind_param("ii", $producto['id'], $id_c);
    $stmt_vc->execute();
}

// Vaciar el carrito después de la compra
unset($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cc.css">
    <title>Confirmación de Compra</title>
</head>

<body>
    <div class="full">
        <div class="container">

            <h1>¡Gracias por tu compra!</h1>
            <p>Tu compra ha sido procesada exitosamente. A continuación, te mostramos los detalles:</p>

            <h2>Detalles de la Compra</h2>
            <p><strong>ID de la Compra:</strong> <?= htmlspecialchars($id_c) ?></p>
            <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
            <p><strong>Subtotal:</strong> $<?= number_format($subtotal_total, 2) ?></p>
            <p><strong>IVA (16%):</strong> $<?= number_format($iva_total, 2) ?></p>
            <hr>
            <h3>Productos Comprados:</h3>
            <hr>
            <ul>
                <?php foreach ($productos as $producto): ?>
                    <div class="li-pro">
                        <li>
                            <?php
                            $ruta_imagen = htmlspecialchars($producto['imagen']);
                            $imagen = file_exists($ruta_imagen) ? $ruta_imagen : 'error.png';
                            ?>
                            <div class="head">
                                <img style="height: 18vh;" src="<?= $imagen ?>"
                                    alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                <strong><?= htmlspecialchars($producto['nombre']) ?></strong>
                            </div>
                            <p>Precio: $<?= number_format($producto['precio'], 2) ?></p>
                            <p style="display: none;">Cantidad: <?= $producto['cantidad'] ?></p>
                            <p>Subtotal: $<?= number_format($producto['subtotal'], 2) ?></p>
                            <p>IVA: $<?= number_format($producto['iva'], 2) ?></p>
                        </li>
                    </div>
                <?php endforeach; ?>
            </ul>
            <hr>
            <h3>Información del Usuario</h3>
            <?php
            //detalles del usuario
            $sql_usuario = "SELECT nom_u, ap_u, correo_u, telefono FROM usuario WHERE id_u = ?";
            $stmt_usuario = $conn->prepare($sql_usuario);
            $stmt_usuario->bind_param("i", $id_u);
            $stmt_usuario->execute();
            $result_usuario = $stmt_usuario->get_result();
            $usuario = $result_usuario->fetch_assoc();
            ?>

            <p><strong>No. Tarjeta Usada:</strong> **** **** ****
                <?= htmlspecialchars(substr($num_tarjeta_usada, -4)) ?>
            </p>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nom_u'] . ' ' . $usuario['ap_u']) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo_u']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
            <div class="head">
                <p class="reg"><a href="cliente.php">Volver al inicio</a></p>
                <button type="submit" onclick="generarTicket()">Generar Ticket</button>
                <button onclick="window.location.href='lic_vid.php'">Descargar Licencias</button>
            </div>
        </div>
    </div>
</body>

<script>



    function generarTicket() {
        // Obtener los detalles de la compra
        const fechaCompra = new Date().toLocaleString(); // Fecha y hora actual
        const total = '<?= number_format($total, 2) ?>';
        const subtotal = '<?= number_format($subtotal_total, 2) ?>';
        const iva = '<?= number_format($iva_total, 2) ?>';

        //info de los productos
        const productos = <?php echo json_encode($productos); ?>;  // Se pasan los productos desde PHP a JS

        // Generación del tickettttt htlm
        const ventanaImpresion = window.open('', 'PRINT', 'height=600,width=800');

        ventanaImpresion.document.write('<html><head><title>Ticket de Compra</title>');
        ventanaImpresion.document.write('<style>');
        ventanaImpresion.document.write('.ticket { font-family: Arial, sans-serif; width: 600px; padding: 20px; border: 2px solid #ccc; border-radius: 10px; margin: 20px auto; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); background-color: #f9f9f9;}');
        ventanaImpresion.document.write('.header { text-align: center; margin-bottom: 20px; }');

        // Efecto visual para el logo
        ventanaImpresion.document.write('.header img { width: 200px; height: auto; margin-bottom: 10px; transition: transform 0.3s ease-in-out; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }');
        ventanaImpresion.document.write('.header img:hover { transform: scale(1.1); }');  // Efecto al pasar el mouse

        ventanaImpresion.document.write('.bold { font-weight: bold; }');
        ventanaImpresion.document.write('.footer { text-align: center; font-size: 0.8em; color: #555; margin-top: 20px; }');
        ventanaImpresion.document.write('.product { display: flex; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }');
        ventanaImpresion.document.write('.product img { width: 120px; height: 120px; margin-right: 20px; border: 2px solid #333; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease; }');
        ventanaImpresion.document.write('.product img:hover { transform: scale(1.1); }');
        ventanaImpresion.document.write('.product-details { flex: 1; }');
        ventanaImpresion.document.write('.product-details p { margin: 5px 0; }');
        ventanaImpresion.document.write('.exclusivo { color: #ff9800; font-weight: bold; font-size: 1.2em; }');
        ventanaImpresion.document.write('</style>');
        ventanaImpresion.document.write('</head><body>');

        ventanaImpresion.document.write('<div class="ticket">');

        // Header con logo y título
        ventanaImpresion.document.write('<div class="header">');
        ventanaImpresion.document.write('<img src="imagenes/juegos/logo-modified.png" alt="Logo de la Empresa">');  // Asegúrate de que la ruta sea correcta
        ventanaImpresion.document.write('<h2>Ticket de Compra</h2>');
        ventanaImpresion.document.write('</div>');

        // Detalles de la compra
        ventanaImpresion.document.write('<p><span class="bold">Fecha y Hora:</span> ' + fechaCompra + '</p>');
        ventanaImpresion.document.write('<p><span class="bold">Subtotal:</span> $' + subtotal + '</p>');
        ventanaImpresion.document.write('<p><span class="bold">IVA (16%):</span> $' + iva + '</p>');
        ventanaImpresion.document.write('<p><span class="bold">Total:</span> <span style="color: green;">$' + total + '</span></p>');

        // Detalles de los productos
        ventanaImpresion.document.write('<h3>Productos Comprados:</h3>');
        productos.forEach(producto => {
            ventanaImpresion.document.write('<div class="product">');
            const imagen = producto.imagen ? producto.imagen : 'error.png';
            ventanaImpresion.document.write('<img src="' + imagen + '" alt="' + producto.nombre + '">');
            ventanaImpresion.document.write('<div class="product-details">');
            ventanaImpresion.document.write('<p><span class="bold">' + producto.nombre + '</span></p>');
            ventanaImpresion.document.write('<p>Cantidad: ' + producto.cantidad + '</p>');
            ventanaImpresion.document.write('<p>Subtotal: $' + producto.subtotal.toFixed(2) + '</p>');
            ventanaImpresion.document.write('<p>IVA: $' + producto.iva.toFixed(2) + '</p>');
            ventanaImpresion.document.write('<p class="exclusivo">¡Producto Exclusivo Comprado!</p>');  // Agregamos un mensaje de exclusividad
            ventanaImpresion.document.write('</div>');
            ventanaImpresion.document.write('</div>');
        });


        ventanaImpresion.document.write('<div class="footer">Gracias por tu compra! <br> Black-Games </div>');
        ventanaImpresion.document.write('</div>');

        ventanaImpresion.document.write('</body></html>');

        ventanaImpresion.document.close();
        ventanaImpresion.focus();
        ventanaImpresion.print();
        ventanaImpresion.close();
    }



</script>

</html>

<?php
$conn->close();
?>