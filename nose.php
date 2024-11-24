<?php
session_start();
include('php/conec.php');

if (!isset($_SESSION['id_u'])) {
    header('Location: index.php');
    exit;
}

$id_u = $_SESSION['id_u'];

// Verificar si el carrito contiene productos
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: carrito.php'); // Redirigir si no hay productos en el carrito
    exit;
}

// Obtener los productos del carrito
$videojuegos = [];
$total = 0;
$subtotal = 0;
foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
    // Obtener los detalles del videojuego
    $consulta_producto = "SELECT id_v, nom_v, precio FROM videojuegos WHERE id_v = ?";
    $producto = obtenerDatos($conexion, $consulta_producto, [$id_producto]);
    if ($producto) {
        // Calcular el precio total de este videojuego (precio * cantidad)
        $subtotal += $producto[0]['precio'] * $cantidad;
        $total += $producto[0]['precio'] * $cantidad;

        // Agregar el videojuego a la lista
        $videojuegos[] = $producto[0]; // Guardar la información de cada producto
    }
}

// Obtener la tarjeta seleccionada
$tarjeta_id = $_POST['tarjeta_id'] ?? null; // Asegúrate de que la tarjeta se haya seleccionado

// Registrar la compra en la base de datos
$fecha_compra = date('Y-m-d H:i:s');
$sql_compra = "INSERT INTO compra (total, subtotal, id_u) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql_compra);
mysqli_stmt_bind_param($stmt, 'ddi', $total, $subtotal, $id_u);
mysqli_stmt_execute($stmt);

// Obtener el ID de la compra recién insertada
$id_c = mysqli_insert_id($conexion);

// Insertar los productos comprados en la tabla `vc`
foreach ($videojuegos as $videojuego) {
    $sql_vc = "INSERT INTO vc (id_v, id_c) VALUES (?, ?)";
    $stmt_vc = mysqli_prepare($conexion, $sql_vc);
    mysqli_stmt_bind_param($stmt_vc, 'ii', $videojuego['id_v'], $id_c);
    mysqli_stmt_execute($stmt_vc);
}

// Vaciar el carrito después de realizar la compra
unset($_SESSION['carrito']);

// Cerrar la conexión a la base de datos
mysqli_close($conexion);

// Redirigir al usuario a una página de éxito o mostrar un mensaje de confirmación
header('Location: compra_exitosa.php?compra_id=' . $id_c);
exit;
?>