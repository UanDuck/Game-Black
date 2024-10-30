<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/fgl.css">
    <title>Game Black</title>
</head>

<body>
    <div class="full">
        <div class="container">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="color: #c72525;margin-bottom: 11px;font-weight: 550;">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="registrar.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Usuario" name="user" maxlength="50" required>
                    <label for="">Username</label>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Nombre" name="nom" maxlength="50" required>
                    <label for="">Nombre/s</label>
                </div>
                <div class="form-group">
                    <input type="text" name="pa" placeholder="Apellido Paterno" maxlength="50" required>
                    <label for="">Apellido Paterno</label>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Apellido Materno" name="ma" maxlength="50" required>
                    <label for="">Apellido Materno</label>
                </div>
                <div class="form-group">
                    <input type="text" placeholder="5512345678" name="telf" minlength="8" maxlength="10" required>
                    <label for="">Telefono </label>
                </div>

                <div class="form-group">
                    <input type="text" name="email" placeholder="ejemplo@gmail.com" id="email" maxlength="100" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="8 Min.  20 Max." name="contra" id="password" required minlength="8" maxlength="20">
                    <label for="password">Contraseña</label>
                </div>

                <div class="btn-form">
                    <button type="button" onclick="window.location.href='Login.html'">Iniciar Sesión</button>
                    <button type="submit">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>