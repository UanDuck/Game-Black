<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pr_admin.css">
    <link rel="stylesheet" href="css/num_pg.css">
    <title>Producto Admin</title>
</head>

<body>
    <div class="full">
        <h1>Productos disponibles</h1>

        <div class="container">

            <?php
            include('conector.php');
            if ($conexion->connect_error) {
                die('Hmmmmm esta mal   prodcuto php' . $conexion->connect_error);
            }

            $pagina_actual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;  //obtiene el numero de lapag actual
            
            $limit = 1;                 // limita los productos a mostrar   |   este se cambia junto con el de paginacion
            $inic = ($pagina_actual - 1) * $limit; // calcula el umero nuevo en el que iniciara
            
            $consulta = "select id_v, nom_v, desc_v, fecha_lanz, clasif_v, genero_v, precio, imagen from videojuegos limit $inic, $limit";
            $registros = $conexion->query($consulta);

            while ($reg = mysqli_fetch_array($registros)) {
                $id_v = $reg['id_v'];
                echo '<div class="producto">';
                echo '<img src="' . $reg['imagen'] . '" >';
                echo '<h2> ' . $reg['nom_v'] . ' </h2>';
                echo '<p> ' . $reg['desc_v'] . ' </p>';
                echo '<p> ' . $reg['fecha_lanz'] . ' </p>';
                echo '<p>Clasificacion  ' . $reg['clasif_v'] . ' </p>';
                echo '<p>Genero: ' . $reg['genero_v'] . ' </p>';
                echo '<p>Precio: ' . $reg['precio'] . ' </p>';
                echo '</div>';
            }

            include('paginacion.php');
            ?>
        </div>
</body>

</html>