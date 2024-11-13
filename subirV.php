<?php
include('conector.php');

$tit = $_POST['titulo'];
$desc = $_POST['desc'];
$fecha = $_POST['flanz'];
$clasif = $_POST['clasifica'];
$gen = $_POST['genero'];
$prec = $_POST['precio'];
$tp = 'imagenes/juegos/';
$tp = $tp . basename($_FILES['uploaderfile']['name']);


if (move_uploaded_file($_FILES['uploaderfile']['tmp_name'], $tp)) {
    $sql = "INSERT INTO videojuegos(nom_v,desc_v,fecha_lanz,clasif_v,genero_v,precio, imagen) VALUES ('$tit','$desc','$fecha','$clasif','$gen','$prec', '$tp')";
    if ($conexion->query($sql) === TRUE) {
        header('location: alta_pr.php');
    } else {
        echo "error al insertar videojuego: " . $conexion->error;
    }
} else {
    echo "Ha ocurrido un error al subir el archivo, intenta de nuevo.<br>";
}

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}


$conexion->close();
?>