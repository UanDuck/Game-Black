<?php
include('php/conec.php');

$consulta = "select count(*) ct from videojuegos"; // contar todos los registros
$ex_consul = $conexion->query($consulta);
$reg = mysqli_fetch_array($ex_consul);
$total = $reg['ct']; // guarda el total de registros contados en la variable $total

$paginas = ceil($total / $limit);  // ceil para redondear hacia arriba

echo '<div class="paginacion" style="position: fixed;" >';

for ($i = 1; $i <= $paginas; $i++) {
    if ($i == $pagina_actual) {
        echo '<span>' . $i . '</span>'; // muestra la pagina actual
    } else {
        echo '<a href="productos.php?pagina=' . $i . '">' . $i . '</a>'; // enlaza a otras páginas
    }
}

echo '</div>';
?>