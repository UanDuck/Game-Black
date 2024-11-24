<?php
session_start();
include('php/conec.php');

// Verificar si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: cliente.php'); // Redirigir si no hay productos en el carrito
    exit;
}

$imagenes = [];
foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
    /*
    select nom_v, imagen, id_u, id_tj,usuario,nom_u,num_tj 
    from videojuegos
    join vc using(id_v) 
    join compra using(id_c) 
    join tarjeta using(id_u) 
    join usuario using(id_u) 
    where id_c = (select max(id_c) from compra);
    */ //hdsptm llamen a dios

    $consulta = "select imagen from 
    (select nom_v, imagen, id_u, id_tj,usuario,nom_u,num_tj
    from videojuegos
    join vc using(id_v) 
    join compra using(id_c) 
    join tarjeta using(id_u) 
    join usuario using(id_u) 
    where id_c = (select max(id_c) from compra)) aux";  //subconsul
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();
    $imagenes[] = $producto['imagen'];
}

$zip = new ZipArchive();
$nombreZip = 'licencias.zip';

if ($zip->open($nombreZip, ZipArchive::CREATE) !== true) {
    exit("No se pudo abrir el archivo <$nombreZip>\n");
}

foreach ($imagenes as $imagen) {
    $zip->addFile($imagen, basename($imagen)); //agregamos las licencias(imagenes) al ZIP
}

$zip->close();

header('Content-Type: application/zip'); //descargamos el zip siosi
header('Content-disposition: attachment; filename=' . $nombreZip);
header('Content-Length: ' . filesize($nombreZip));
readfile($nombreZip);
exit;
?>