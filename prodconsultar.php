<?php

$host = 'localhost'; 
$user = 'root';     
$password = '';      
$database = 'gb'; 


$conn = new mysqli($host, $user, $password, $database);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$sql = "SELECT * FROM videojuegos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Videojuegos</title>
    <link rel="stylesheet" href="css/tablas.css">
</head>
<body>
    <h1>Lista de Videojuegos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Fecha de Lanzamiento</th>
                <th>Clasificación</th>
                <th>Género</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nom_v']); ?></td>
                    <td><?php echo htmlspecialchars($row['desc_v']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_lanz']); ?></td>
                    <td><?php echo htmlspecialchars($row['clasif_v']); ?></td>
                    <td><?php echo htmlspecialchars($row['genero_v']); ?></td>
                    <td><?php echo htmlspecialchars($row['precio']); ?></td>
                    <td>
                        <?php if ($row['imagen']) { ?>
                            <img src="imgprod/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen del videojuego" width="100">
                        <?php } ?>
                    </td>
                    <td>
                        <!-- Botón de Modificar -->
                        <a href="prodmodificar.php?id=<?php echo $row['id_v']; ?>">Modificar</a> |
                        <!-- Botón de Eliminar -->
                        <a href="prodeliminar.php?id=<?php echo $row['id_v']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este videojuego?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    // Cerrar
    $conn->close();
    ?>
</body>
</html>
