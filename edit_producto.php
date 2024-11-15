<?php
include('php/conec.php');
if ($conexion->connect_error) {
    die('Hmmmmm esta mal edit_producto' . $conexion->connect_error);
}

$id_v = $_GET['id_v'];

$consulta = "select id_v, nom_v, desc_v, fecha_lanz, clasif_v, genero_v, precio,imagen from videojuegos where id_v=$id_v";
$registros = $conexion->query($consulta);
$reg = mysqli_fetch_array($registros);

$titulo = $reg['nom_v'];
$desc = $reg['desc_v'];
$fecha = $reg['fecha_lanz'];
$clasif = $reg['clasif_v'];
$genero = $reg['genero_v'];
$precio = $reg['precio'];
$imagen = $reg['imagen'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pr_admin.css">
    <link rel="stylesheet" href="css/num_pg.css">
    <link rel="stylesheet" href="css/fgl.css">
    <title>Editar Juego | Admin</title>
</head>

<body>
    <div class="full">
        <h1>Editar Juego </h1>
        <h3>Id: <?php echo $id_v; ?> </h3>
        <h3>Juego: <?php echo "$titulo"; ?> </h3>
        <div class="container">
            <form enctype="multipart/form-data" action="pr_actualizar.php" method="post">
            <input type="hidden" name="id_v" value="<?php echo $id_v; ?>">
                
                
                <label for="">Titulo</label>
                <input type="text" placeholder="Titulo del Videojuego" name="tt" <?php echo "value='$titulo'"; ?>><br><br><br>

                <label for="">Descripcion</label>
                <textarea name="ds" placeholder="Descripcion" id="" cols="30" rows="10"
                value=" "><?php echo $desc ?></textarea> <br><br><br>

                <label for="">Fecha de Lanzamiento</label>
                <input type="date" name="fz" placeholder="Fecha Lanzamiento" value="<?php echo $fecha; ?>"><br><br>

                <input type="text" name="cs" placeholder="Clasificacion" value="<?php echo $clasif; ?>"><br><br>
                <input type="text" name="gn" placeholder="Genero" value="<?php echo $genero; ?>"><br><br>
                <input type="text" name="pc" placeholder="Precio Ej 89.99" value="<?php echo $precio; ?>"><br><br>
                <img style="style= width: 80px;" src="<?php echo $imagen ?>">
                <input type="file" name="ima" placeholder="Foto del juego"><br><br><br>
                <input type="submit" value="Guardar">
            </form>
        </div>
</body>

</html>