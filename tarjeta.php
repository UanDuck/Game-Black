<?php
session_start();
include('php/conec.php');

if (!isset($_SESSION['id_u'])) {
    header('Location: index.php'); // Checamos si el usuario ya ha iniciado sesión
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

// Código modificado para obtener productos del carrito
$videojuegos = [];
if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
        // Consulta a la base de datos para obtener detalles de cada videojuego
        $consulta_producto = "SELECT id_v, nom_v, precio, imagen FROM videojuegos WHERE id_v = ?";
        $producto = obtenerDatos($conexion, $consulta_producto, [$id_producto]);
        if ($producto) {
            // Agregar el producto al array de videojuegos
            $videojuegos[] = $producto[0]; // El resultado es un array, así que tomamos el primer elemento
        }
    }
}

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
    <link rel="stylesheet" href="css/fgl.css">
    <link rel="stylesheet" href="css/tarjetas.css">
    <title>Tarjeta | Game Black</title>
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

            <!-- Tarjetas Registradas -->
            <h1>Tarjetas Registradas</h1>
            <ul>
                <?php if (empty($tarjetas)): ?>
                    <li>No tienes tarjetas registradas.</li>
                <?php else: ?>
                    <form style="list-style: none;" class="tarj" action="confirmar.php" method="post"
                        onsubmit="return validarSeleccion();">
                        <?php foreach ($tarjetas as $tarjeta): ?>
                            <li>
                                <div class="form-group">
                                    <label style="color: white;" for="tarjeta_<?= htmlspecialchars($tarjeta['id_tj']) ?>">
                                        <?= htmlspecialchars($tarjeta['nom_titular']) ?> |
                                        <?= htmlspecialchars($tarjeta['tipo_tj']) ?> |
                                        <?= htmlspecialchars($tarjeta['num_tj']) ?> 
                                    </label>
                                    <input style="transform: translateX(-52%);" type="radio" name="tarjeta"
                                        value="<?= htmlspecialchars($tarjeta['id_tj']) ?>"
                                        id="tarjeta_<?= htmlspecialchars($tarjeta['id_tj']) ?>" required>
                                </div>
                            </li>
                        <?php endforeach; ?> <br>
                        <button type="submit" style="background-color: green; color: white;">Confirmar compra</button>
                    </form>
                <?php endif; ?>
            </ul>

            <button id="add-card-btn">Agregar Otra Tarjeta</button>
            <br><br>

            <div class="add-tarjeta" id="add-tarjeta">
                <h4>Agrega Tu Tarjeta de Crédito | Débito!</h4>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class=" alert alert-danger"
                        style="color: #c72525;margin-bottom: 11px;font-weight: 550; text-align: center;">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <form action="registrarT.php" id="ing-t" method="post">

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
                    <div class="juntos" style="display: flex; flex-direction: row; gap: 3vh;">
                        <div class="form-group fech" style="width: 47%;">
                            <input type="number" placeholder=" " name="dia" minlength="1" maxlength="2" min="1" max="12"
                                required="">
                            <label style="top: 35%;" for="">Mes </label>
                        </div>
                        <h2 style="position: relative; transform: translate(50%, -50%);">/</h2>
                        <div class="form-group fech" style="width: 45%;">
                            <input type="number" placeholder=" " name="anio" minlength="2" maxlength="2" min="24"
                                max="99" required>
                            <label style="top: 35%;" for="">Año </label>
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
    <script>
        function validarSeleccion() {
            const radios = document.getElementsByName('tarjeta');
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    return true; // Al menos una tarjeta está seleccionada
                }
            }
            alert('Por favor, selecciona una tarjeta antes de continuar.');
            return false; // Evita el envío del formulario
        }
    </script>
    <script src="js/display-tarjeta.js"></script>
</body>

</html>