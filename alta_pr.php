<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pr_admin.css">
    <link rel="stylesheet" href="css/fgl.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Tienda</title>
</head>

<body>

    <div class="not">
        <div class="not_body">
            <img src="img/accept.png" alt="Succes" class="not-icon">
            Juego Subido! &#128640
        </div>
        <div class="not_progres"></div>
    </div>

    <div class="full">
        <h1>Alta Videojuego</h1>
        <div class="container">
            <form enctype="multipart/form-data" action="subirV.php" method="post">
                <input type="text" placeholder="Titulo del Videojuego" name="titulo" required><br><br>
                <textarea name="desc" placeholder="Descripcion" id="" cols="30" rows="10"></textarea><br><br>
                <input type="date" name="flanz" placeholder="Fecha Lanamiento" required><br><br>
                <input type="text" name="clasifica" placeholder="Clasificacion" required><br><br>
                <input type="text" name="genero" placeholder="Genero" required><br><br>
                <input type="text" name="precio" placeholder="Precio Ej 89.99" required><br><br>
                <input type="file" name="uploaderfile" placeholder="Foto del juego" required><br><br><br>
                <input type="submit" value="Registrar">
            </form>
        </div>
    </div>
</body>

</html>