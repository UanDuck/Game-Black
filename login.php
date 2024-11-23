<?php
session_start();

// Definir la clave secreta y el método de encriptación
define('SECRET_KEY', 'mi_clave_secreta'); 
define('METHOD', 'AES-256-CBC'); // Método de cifrado

// desencriparcontra
function decryptPassword($encryptedPassword) {
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedPassword), 2); 
    return openssl_decrypt($encryptedData, METHOD, SECRET_KEY, 0, $iv); 
}
session_start();

$host = 'localhost';
$dbname = 'gb'; 
$username = 'root'; 
$password = ''; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usernameInput = $_POST['usuario']; 
        $contraseñaInput = trim($_POST['contrasenia_u']);  // Eliminar espacios adicionales

        // Obtener usuario y tipo
        $stmt = $conn->prepare("SELECT id_u,contrasenia_u, tipo_u FROM usuario WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usernameInput);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $encryptedPassword = $row['contrasenia_u']; 
            $tipoUsuario = $row['tipo_u']; 

            // Desencriptar la contraseña
            $decryptedPassword = decryptPassword($encryptedPassword);

            // Verificar si la contraseña ingresada coincide con la desencriptada
            echo "Contraseña desencriptada: " . $decryptedPassword . "<br>"; // Depuración
            echo "Contraseña ingresada: " . $contraseñaInput . "<br>"; // Depuración

            if ($contraseñaInput === $decryptedPassword) {
                $_SESSION['id_u'] = $row['id_u'];
                if ($tipoUsuario == 'admin') {
                    echo "<script>alert('Inicio de sesión exitoso. Bienvenido, administrador!'); window.location.href='consultarusuario.php';</script>";
                } else {
                    echo "<script>alert('Inicio de sesión exitoso. Bienvenido, usuario!'); window.location.href='cliente.php';</script>";
                }
                exit();
            } else {
                echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('El usuario no existe.'); window.history.back();</script>";
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
