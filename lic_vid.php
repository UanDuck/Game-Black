<?php
ob_start(); // Iniciar el buffer de salida
session_start();
include('php/conec.php');
$conn = $conexion;

// Verificar si el carrito está vacío
/*
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header('Location: cliente.php'); // Redirigir si no hay productos en el carrito
    exit;
}
*/

$imagenes = [];
$consulta = "select imagen from videojuegos where id_v in (select id_v from vc where id_c = (select max(id_c) from compra))";
$stmt = $conn->prepare($consulta);
$stmt->execute();
$result = $stmt->get_result();

//guardamos las imagenes de la consulta en el array
while ($producto = $result->fetch_assoc()) {
    $imagenes[] = $producto['imagen'];
}


if (empty($imagenes)) { //sino se encontraron imagenes mandar este error
    echo ("No se encontraron imágenes para descargar."); 
}


$zip = new ZipArchive(); //creamos el archivo zio
$nombreZip = 'licencias.zip';

if ($zip->open($nombreZip, ZipArchive::CREATE) !== true) {
    echo ("No se pudo abrir el archivo <$nombreZip>\n");
}

foreach ($imagenes as $imagen) {
    if (file_exists($imagen)) {//si el archivo(ruta de la imagen) existe, agregamos al zip
        $zip->addFile($imagen, basename($imagen)); //agregamos las licencias(imagenes) al zip
    } else {
        error_log("El archivo $imagen no existe y no se pudo agregar al ZIP.");
    }
}

$zip->close();

//verificamos si el archivo zip se creo
if (!file_exists($nombreZip)) {
    error_log("No se pudo crear el archivo ZIP.");
}

// Enviar el archivo ZIP al navegador
header('Content-Type: application/zip'); // Tipo de contenido
header('Content-Disposition: attachment; filename="' . basename($nombreZip) . '"'); // Forzar descarga
header('Content-Length: ' . filesize($nombreZip)); // Longitud del contenido
readfile($nombreZip); // Leer el archivo y enviarlo al navegador

// Eliminar el archivo ZIP del servidor si ya no es necesario
unlink($nombreZip);
?>