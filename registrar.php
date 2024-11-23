<?php
session_start();


// Definir una clave secreta para encriptación y desencriptación
define('SECRET_KEY', 'mi_clave_secreta'); // Cambia esto por una clave más segura y secreta
define('METHOD', 'AES-256-CBC'); // Método de cifrado

// Función para encriptar la contraseña
function encryptPassword($contra) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(METHOD)); // IV aleatorio
    $encryptedPassword = openssl_encrypt($contra, METHOD, SECRET_KEY, 0, $iv); // Encriptar
    // Guardar tanto la contraseña encriptada como el IV para poder desencriptarla después
    return base64_encode($encryptedPassword . '::' . $iv); 
}

// Función para desencriptar la contraseña
function decryptPassword($encryptedPassword) {
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedPassword), 2); // Separar datos
    return openssl_decrypt($encryptedData, METHOD, SECRET_KEY, 0, $iv); // Desencriptar
}


include('conector.php');





$usern = $_POST['user'];
$nombr = $_POST['nom'];  
$ap = $_POST['pa'];
$am = $_POST['ma'];
$telf = $_POST['telf'];
$correo = $_POST['email'];
$contra = $_POST['confirmar_contra'];
$encript_contra = encryptPassword($_POST['confirmar_contra']); // Encriptar la contraseña








if (empty($correo) || empty($contra)) {
    $_SESSION['error'] = 'El correo electrónico y la contraseña son obligatorios.';
    header('Location: r_usuario.php');
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'El correo electrónico es inválido.';
    header('Location: r_usuario.php');
    exit;
}

if (!preg_match('/^[a-zA-Z0-9]+$/', $contra)) {
    $_SESSION['error'] = 'La contraseña es inválida. Solo debe incluir letras y números.';
    header('Location: r_usuario.php');
    exit;
}

$consulta_existe = "SELECT * FROM usuario WHERE correo_u=?";
$pre = mysqli_prepare($conexion, $consulta_existe);
mysqli_stmt_bind_param($pre, "s", $correo);
mysqli_stmt_execute($pre);
$resultado_existe = mysqli_stmt_get_result($pre);
$existe = mysqli_num_rows($resultado_existe);



$sql = "INSERT INTO usuario (usuario, nom_u, ap_u, am_u, correo_u, contrasenia_u, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)";
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