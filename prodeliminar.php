<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'gb';


$conn = new mysqli($host, $user, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


if (isset($_GET['id'])) {
    $id_v = $_GET['id'];


    $sql = "SELECT imagen FROM videojuegos WHERE id_v = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_v);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Eliminar la imagen del servidor
        $imagen = $row['imagen'];
        if (file_exists("$imagen")) {
            unlink("$imagen");
        }

        
        $sql = "DELETE FROM videojuegos WHERE id_v = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_v);

        if ($stmt->execute()) {
            echo "El videojuego ha sido eliminado exitosamente.";
        } else {
            echo "Error al eliminar el videojuego: " . $stmt->error;
        }

       
        header("Location: prodconsultar.php");
        exit();
    } else {
        echo "No se encontró el videojuego.";
    }
}

// Cerrar
$conn->close();
?>
