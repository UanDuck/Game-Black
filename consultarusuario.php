<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "gb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener todos los registros de la tabla 'usuario'
$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Gestión de Usuarios</title>
    <link rel="stylesheet" href="css/tables.css">
</head>
<body>

<header class="header">
        <h1 class="site-title" id="site-title">The Black Games</h1>
</header>

<!-- Botón para abrir el sidebar -->
<button class="menu-btn" id="menu-toggle">&#9776;</button> 

<br><br><br>

<aside class="sidebar" id="sidebar">
    <button class="close-btn" id="close-sidebar">&times;</button>
    <br><br><br>
    <nav class="sidebar-nav">
        <a href="login.html">• Cerrar sesión</a>
        <a href="registroadmin.html">• Ingresar nuevo usuario</a>
        <a href="consultarusuario.php">• Modificar registros de usuarios</a>
        <a href="prodingresar.html">• Ingresar productos</a>
        <a href="prodconsultar.php">• Modificar productos</a>
    </nav>
</aside>

<script>
    // Mostrar/ocultar el sidebar
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('visible');
    });

    document.getElementById('close-sidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('visible');
    });

    // Efecto de cambio de tamaño en el título al deslizar
    window.addEventListener('scroll', function() {
        const title = document.getElementById('site-title');
        if (window.scrollY > 50) {
            title.classList.add('shrink');
        } else {
            title.classList.remove('shrink');
        }
    });
</script>

<div>

    <br><br><br><br><br><br><br>

    <h1>Listado de Usuarios</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Email</th>
            <th>Tipo Usuario</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        <?php


        // Mostrar registros
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_u'] . "</td>";
                echo "<td>" . $row['usuario'] . "</td>";
                echo "<td>" . $row['nom_u'] . "</td>";
                echo "<td>" . $row['ap_u'] . "</td>";
                echo "<td>" . $row['am_u'] . "</td>";
                echo "<td>" . $row['correo_u'] . "</td>";
                echo "<td>" . $row['tipo_u'] . "</td>";
                echo "<td>" . $row['telefono'] . "</td>";
                echo "<td>
                        <a class='btn-editar' href='editarusuario.php?id_u=" . $row['id_u'] . "'>Modificar</a> <br> <br> . 
                        <a class='btn-eliminar' href='eliminarusuario.php?id_u=" . $row['id_u'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'>Eliminar</a>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No se encontraron registros</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>
