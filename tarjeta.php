<?php
session_start();
include('php/conec.php');

if (!isset($_SESSION['id_u'])) {
    header('Location: index.php'); // checamos si el usuario ya ha iniciado sesion
    exit;
}

$id_u = $_SESSION['id_u'];

function obtenerDatos($conexion, $sql, $parametros = [])
{
    $stmt = mysqli_prepare($conexion, $sql);
    
    if ($parametros) {
        // Asumiendo que todos los parámetros son de tipo string
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($parametros)), ...$parametros);
    }
    
    mysqli_stmt_execute($stmt);
    $result_cons = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result_cons, MYSQLI_ASSOC);
}

$cons_juegos = "SELECT * FROM vc JOIN videojuegos USING (id_v) WHERE id_c IN (SELECT id_c FROM compra WHERE id_u = ?)";
$videojuegos = obtenerDatos($conexion, $cons_juegos, [$id_u]);

$tarjetas = obtenerDatos($conexion, "SELECT * FROM tarjeta WHERE id_u = ?", [$id_u]);

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, follow" />
    <link rel="stylesheet" href="css/notifica.css">
    <link rel="stylesheet" href="css/style.css">
    <title> Tarjeta | Game Black</title>
    <style>
        .add-tarjeta {
            display: none;
        }
    </style>
</head>

<body>
    <div class="full">
        <div class="container">
            <h1>Tu Carrito</h1>
            <ul>
                <?php if (empty($videojuegos)): ?>
                    <li>No tienes videojuegos en tu carrito.</li>
                <?php else: ?>
                    <?php foreach ($videojuegos as $videojuego): ?>
                        <li><?= htmlspecialchars($videojuego['nom_v']) ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <h1>Tarjetas Registradas</h1>
            <ul>
                <?php if (empty($tarjetas)): ?>
                    <li>No tienes tarjetas registradas.</li>
                <?php else: ?>
                    <?php foreach ($tarjetas as $tarjeta): ?>
                        <li><?= htmlspecialchars($tarjeta['nom_titular']) ?> - <?= htmlspecialchars($tarjeta['tipo_tj']) ?> -
                            **** **** **** <?= htmlspecialchars(substr($tarjeta['num_tj'], -4)) ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <button id="add-card-btn" style="margin-top: 20px;">Agregar Otra Tarjeta</button>

            <div class ="add-tarjeta" id="add-tarjeta">
                <h1>Agrega Tu Tarjeta de Crédito | Débito!</h1>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"
                        style="color: #c72525;margin-bottom: 11px;font-weight: 550; text-align: center;">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <form action="registrarT.php" method="post">

                    <div class="form-group">
                        <input type="text" placeholder=" " name="titular" maxlength="50" required>
                        <label for="">Titular</label>
                    </div>

                    <div class="form-group">
                        <select name="tp_tj" id="" required>
                            <option value="Debito">Debito</option>
                            <option value="Credito">Credito</option>
                        </select>
                    </div>

                    <h3 style="display: contents;">Fecha de Vencimiento</h3>
                    <div class="juntos" style=" display: flex; flex-direction: row; gap: 3vh;">
                        <div class="form-group">
                            <input type="number" placeholder=" " name="dia" minlength="1" maxlength="2" min="1" max="12"
                                required>
                            <label for="">Mes </label>
                        </div>
                        <div class="form-group">
                            <input type="number" placeholder=" " name="anio" minlength="2" maxlength="2" min="24"
                                required>
                            <label for="">Año </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" placeholder="   " name="nt" id="nt" required minlength="8" maxlength="16">
                        <label for="password">Numero de Tarjeta</label>
                    </div>

                    <div class="form-group">
                        <input type="password" placeholder="XXX" name="cvv" id="cvv" required minlength="3"
                            maxlength="3">
                        <label for="password">CVV</label>
                    </div>
                    <button type="submit"
                        style="width: 94%;background: green;color: white;font-weight: 700;border: none;margin-left: 2%;">Registrar
                        Tarjeta</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>