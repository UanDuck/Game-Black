<?php
session_start();
include('php/conec.php');

if (!isset($_SESSION['id_u'])) {
    $_SESSION['error'] = 'Debes iniciar sesión para realizar una compra.';
    header('Location: index.php'); 
    exit;
}

$idu = $_SESSION['id_u']; 

$tt = $_POST['titular'];
$tipo_tg = $_POST['tp_tj'];
$mes = $_POST['dia'];
$anio = $_POST['anio'];
$num_tg = $_POST['nt'];
$cvv = $_POST['cvv'];


if (!preg_match('/^\d{3}$/', $cvv)) {
    $_SESSION['error'] = 'El CVV es inválido. Debe ser de 3 o 4 dígitos.';
    header('Location: tarjeta.php');
    exit;
}
if (!checkdate($mes, 1, $anio)) {
    $_SESSION['error'] = 'Fecha de vencimiento inválida.';
    header('Location: tarjeta.php');
    exit;
}


$fechav=("$anio/$mes");

$sql = "INSERT INTO tarjeta (nom_titular, tipo_tj, fecha_venc, num_tj, cvv, id_u) VALUES (?, ?, ?, ?, ?, ?)";
$pre = mysqli_prepare($conexion, $sql);
if ($pre === false) {
    $_SESSION['error'] = 'Error en la preparación de la consulta.';
    header('Location: tarjeta.php');
    exit;
}

mysqli_stmt_bind_param($pre, "ssssss", $tt, $tipo_tg, $fechav, $num_tg, $cvv, $idu);

if (!mysqli_stmt_execute($pre)) {
    $_SESSION['error'] = 'Error al registrar la Tarjeta: ' . mysqli_error($conexion);
    header('Location: tarjeta.php');
    exit;
}

if (mysqli_stmt_affected_rows($pre) > 0) {
    header('Location: tarjeta.php?exito=Tarjeta Registrada');
    exit;
} else {
    $_SESSION['error'] = 'Error al registrar la Tarjeta. Inténtelo de nuevo.';
    header('Location: tarjeta.php');
    exit;
}

mysqli_stmt_close($pre);
mysqli_close($conexion);
?>