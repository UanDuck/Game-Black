<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "gb"; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificaaaa
if (isset($_GET['id_u'])) {
    $id_u = $_GET['id_u']; 

    
    $sql = "DELETE FROM usuario WHERE id_u = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_u); 

    if ($stmt->execute()) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }

   
    header("Location: consultarusuario.php");  
    exit();
}

$conn->close();
?>
