<?php
session_start();

include('conector.php');

$usern = $_POST['user'];
$nombr = $_POST['nom'];  
$ap = $_POST['pa'];
$am = $_POST['ma'];
$telf = $_POST['telf'];
$correo = $_POST['email'];
$contra = $_POST['confirmar_contra'];




if (empty($correo) || empty($contra)) {
    $_SESSION['error'] = 'El correo electrónico y la contraseña son obligatorios.';
    header('Location: index.php');
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'El correo electrónico es inválido.';
    header('Location: index.php');
    exit;
}

if (!preg_match('/^[a-zA-Z0-9]+$/', $contra)) {
    $_SESSION['error'] = 'La contraseña es inválida. Solo debe incluir letras y números.';
    header('Location: index.php');
    exit;
}

$consulta_existe = "SELECT * FROM registro WHERE email=?";
$pre = mysqli_prepare($conexion, $consulta_existe);
mysqli_stmt_bind_param($pre, "s", $correo);
mysqli_stmt_execute($pre);
$resultado_existe = mysqli_stmt_get_result($pre);
$existe = mysqli_num_rows($resultado_existe);

$encript_contra = password_hash($contra, PASSWORD_BCRYPT);

$sql = "INSERT INTO registro (username, nombre, ap_p, ap_m, email, pass, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
$pre = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($pre, "sssssss", $usern, $nombr, $ap, $am, $correo, $encript_contra, $telf);
mysqli_stmt_execute($pre);

if (mysqli_stmt_affected_rows($pre) > 0) {
    header('location: index.php?exito=Registro exitoso');
    exit;
} else {
    $_SESSION['error'] = 'Error al registrar el usuario. Inténtelo de nuevo.';
    header('location:index.php');
    exit;
}

mysqli_stmt_close($pre);
mysqli_close($conexion);
?>