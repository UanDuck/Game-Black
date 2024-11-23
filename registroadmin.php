<?php
$host = 'localhost';
$dbname = 'gb'; 
$username = 'root'; 
$password = ''; 

// Definir una clave secreta para encriptación y desencriptación
define('SECRET_KEY', 'mi_clave_secreta'); // Cambia esto por una clave más segura y secreta
define('METHOD', 'AES-256-CBC'); // Método de cifrado

// Función para encriptar la contraseña
function encryptPassword($password) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(METHOD)); // IV aleatorio
    $encryptedPassword = openssl_encrypt($password, METHOD, SECRET_KEY, 0, $iv); // Encriptar
    // Guardar tanto la contraseña encriptada como el IV para poder desencriptarla después
    return base64_encode($encryptedPassword . '::' . $iv); 
}

// Función para desencriptar la contraseña
function decryptPassword($encryptedPassword) {
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedPassword), 2); // Separar datos
    return openssl_decrypt($encryptedData, METHOD, SECRET_KEY, 0, $iv); // Desencriptar
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $nombre = $_POST['nombre'];
        $apellidoP = $_POST['apellidop'];
        $apellidoM = $_POST['apellidom'];
        $email = $_POST['email'];

        // Encriptar la contraseña
        $contraseña = encryptPassword($_POST['contraseña']); // Encriptar la contraseña
        $tipo = $_POST['tipo'];
        $telefono = $_POST['telefono'];
        
        // Verificar si el coorrer ya está registrado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE correo_u = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "<script>alert('El email ya está registrado. Por favor, use otro.'); window.history.back();</script>";
            exit();
        }

        // Generar un nombre de usuario aleatorio basado en el nombre
        $username = strtolower($nombre) . rand(100, 999);

        
        $sql = "INSERT INTO usuario (usuario, nom_u, ap_u, am_u, correo_u, contrasenia_u, tipo_u, telefono)
                VALUES (:usuario, :nom_u, :ap_u, :am_u, :correo_u, :contrasenia_u, :tipo_u, :telefono)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $username);
        $stmt->bindParam(':nom_u', $nombre);
        $stmt->bindParam(':ap_u', $apellidoP);
        $stmt->bindParam(':am_u', $apellidoM);
        $stmt->bindParam(':correo_u', $email);
        $stmt->bindParam(':contrasenia_u', $contraseña); // Contraseña encriptada
        $stmt->bindParam(':tipo_u', $tipo);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_INT);

        // Ejecutarconsulta
        if ($stmt->execute()) {
            echo "<script>alert('Registro exitoso. Bienvenido!'); window.location.href='registroadmin.html';</script>";
            exit();
        } else {
            echo "<script>alert('Error al registrar los datos.'); window.history.back();</script>";
        }
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
