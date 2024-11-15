<?php
include('conector.php');
if ($conexion->connect_error) {
    die('Hmmmmm esta mal actualizae_pr' . $conexion->connect_error);
}

$idj = $_POST['id_v'];
$tit = $_POST['tt'];
$desc = $_POST['ds'];
$fecha = $_POST['fz'];
$clasif = $_POST['cs'];
$gen = $_POST['gn'];
$prec = $_POST['pc'];
$tp = 'imagenes/juegos/';
$tp = $tp . basename($_FILES['ima']['name']);


if (move_uploaded_file($_FILES['ima']['tmp_name'], $tp)) {
    $sql = "UPDATE videojuegos SET nom_v='$tit', desc_v='$desc', fecha_lanz='$fecha', clasif_v='$clasif', genero_v='$gen', precio='$prec', imagen='$tp' WHERE id_v='$idj'";
    if ($conexion->query($sql) === TRUE) {
        header('location: edit_producto.php');
    } else {
        echo "error al ACTUALIZAR videojuego: " . $conexion->error;
    }
} else {
    echo "Ha ocurrido un error al subir el archivo, intenta de nuevo.<br>";
}

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


$conexion->close();
?>