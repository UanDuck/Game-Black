<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pr_admin.css">
    <title>Producto Admin</title>
</head>

<body>
    <div class="full">
        <h1>Productos disponibles</h1>

        <div class="container">

            <?php include('conector.php');
            if ($conexion->connect_error) {
                die('Hmmmmm esta mal   prodcuto php' . $conexion->connect_error);
            }
            $consulta = "select id_v,nom_v,desc_v,fecha_lanz,clasif_v,genero_v,precio,imagen from videojuegos";
            $registros = $conexion->query($consulta);

            while ($reg = mysqli_fetch_array($registros)) {
                $id_v = $reg['id_v'];
                echo '<div class="producto">';
                echo '<img src=' . $reg['imagen'] . ' >';
                echo '<h2> ' . $reg['nom_v'] . ' </h2>';
                echo '<p> ' . $reg['desc_v'] . ' </p>';
                echo '<p> ' . $reg['fecha_lanz'] . ' </p>';
                echo '<p> ' . $reg['clasif_v'] . ' </p>';
                echo '<p> ' . $reg['genero_v'] . ' </p>';
                echo '<p> ' . $reg['precio'] . ' </p>';
                echo '</div>';
            }
            ?>

        </div>
</body>

</html>