<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'gb';

// Crear conexión
$conn = new mysqli($host, $user, $password, $database);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el id del videojuego a modificar
if (isset($_GET['id'])) {
    $id_v = $_GET['id'];

    // Obtener los datos del videojuego
    $sql = "SELECT * FROM videojuegos WHERE id_v = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_v);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "No se encontró el videojuego.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los nuevos valores del formulario
    $nom_v = $_POST['nom_v'];
    $desc_v = $_POST['desc_v'];
    $fecha_lanz = $_POST['fecha_lanz'];
    $clasif_v = $_POST['clasif_v'];
    $genero_v = $_POST['genero_v'];
    $precio = $_POST['precio'];
    $imagen = $row['imagen']; // Mantener la imagen original si no se sube una nueva

    
    if ($_FILES['imagen']['name']) {
        $target_path = "imagenes/juegos/" . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_path)) {
            // Eliminar la imagen antigua
            if (file_exists("imagenes/juegos/$imagen")) {
                unlink("imagenes/juegos/$imagen");
            }
            $imagen = basename($_FILES['imagen']['name']);
        } else {
            echo "Error al subir la imagen.";
            exit();
        }
    }

   
    $sql = "UPDATE videojuegos SET nom_v = ?, desc_v = ?, fecha_lanz = ?, clasif_v = ?, genero_v = ?, precio = ?, imagen = ? WHERE id_v = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nom_v, $desc_v, $fecha_lanz, $clasif_v, $genero_v, $precio, $imagen, $id_v);

    if ($stmt->execute()) {
        echo "El videojuego ha sido modificado con éxito.";
        header("Location: prodconsultar.php");
        exit();
    } else {
        echo "Error al modificar el videojuego: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Videojuego</title>
    <link rel="stylesheet" href="css/formulariosprod.css">
</head>
<body>
    <h1>Modificar Videojuego</h1>
    <form enctype="multipart/form-data" method="POST">
        <label for="nom_v">Nombre:</label>
        <input type="text" id="nom_v" name="nom_v" value="<?php echo htmlspecialchars($row['nom_v']); ?>" required><br><br>

        <label for="desc_v">Descripción:</label>
        <textarea id="desc_v" name="desc_v" rows="4" cols="50"><?php echo htmlspecialchars($row['desc_v']); ?></textarea><br><br>

        <label for="fecha_lanz">Fecha de Lanzamiento:</label>
        <input type="date" id="fecha_lanz" name="fecha_lanz" value="<?php echo htmlspecialchars($row['fecha_lanz']); ?>" required><br><br>



        <label for="genero_v" value="<?php echo htmlspecialchars($row['genero_v']); ?>">Género:</label>
<select id="genero_v" name="genero_v">
    <option value="accion">Acción</option>
    <option value="aventura">Aventura</option>
    <option value="rpg">RPG</option>
    <option value="deporte">Deporte</option>
    
</select>

<label for="clasif_v">Clasificación:</label>
<select id="clasif_v" name="clasif_v" value="<?php echo htmlspecialchars($row['clasif_v']); ?>">
    <option value="todo_publico">Todo Público</option>
    <option value="mayores_12">Mayores de 12</option>
    <option value="mayores_18">Mayores de 18</option>
   
</select>


        <label for="precio">Precio:</label>
        <input type="number" step="0.01" id="precio" name="precio" value="<?php echo htmlspecialchars($row['precio']); ?>"><br><br>

        <!-- Mostrar la imagen actual -->
        <label for="imagen">Imagen Actual:</label><br>
        <?php if ($row['imagen']) { ?>
            <img src="imgprod/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen del videojuego" width="200"><br><br>
            <p>Si deseas cambiar la imagen, selecciona una nueva imagen.</p>
        <?php } else { ?>
            <p>No hay imagen disponible.</p>
        <?php } ?>

        <!-- Subir una nueva imagen -->
        <label for="imagen">Seleccionar nueva imagen (opcional):</label>
        <input type="file" id="imagen" name="imagen" accept="image/*"><br><br>

        <input type="submit" value="Modificar Producto">
    </form>

    <p><a href="prodconsultar.php">Volver a la lista</a></p>
</body>
</html>
