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
                <div class="alert alert-danger" style="color: #c72525;margin-bottom: 11px;font-weight: 550; text-align: center;">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="registrar.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder=" " name="user" maxlength="50" required>
                    <label for="">Username</label>
                </div>
                <div class="form-group">
                    <input type="text" placeholder=" " name="nom" maxlength="50" required>
                    <label for="">Nombre/s</label>
                </div>
                <div class="form-group">
                    <input type="text" name="pa" placeholder=" " maxlength="50" required>
                    <label for="">Apellido Paterno</label>
                </div>
                <div class="form-group">
                    <input type="text" placeholder=" " name="ma" maxlength="50" required>
                    <label for="">Apellido Materno</label>
                </div>
                <div class="form-group">
                    <input type="text" placeholder=" " name="telf" minlength="8" maxlength="10" required>
                    <label for="">Telefono </label>
                </div>

                <div class="form-group">
                    <input type="text" name="email" placeholder="@g  . " id="email" maxlength="100" required>
                    <label for="email">Correo</label>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="8 - 20" name="contrasena" id="contrasena" required minlength="8" maxlength="20">
                    <label for="password">Contraseña</label>
                </div>
                <div class="form-group">
                    <input type="password" placeholder=" " name="confirmar_contra" id="confirmar_contra" required minlength="8" maxlength="20">
                    <label for="password">Confirmar Contraseña</label>
                </div>

                <div class="btn-form">
                    <button type="button" onclick="window.location.href='Login.html'">Iniciar Sesión</button>
                    <button type="submit">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>