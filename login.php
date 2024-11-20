<?php
// Definir la clave secreta y el método de encriptación
define('SECRET_KEY', 'mi_clave_secreta'); // Cambia esto por una clave más segura y secreta
define('METHOD', 'AES-256-CBC'); // Método de cifrado

// desencriparcontra
function decryptPassword($encryptedPassword) {
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedPassword), 2); // Separar datos
    return openssl_decrypt($encryptedData, METHOD, SECRET_KEY, 0, $iv); // Desencriptar
}

$host = 'localhost';
$dbname = 'gb'; 
$username = 'root'; 
$password = ''; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $usernameInput = $_POST['usuario']; 
        $contraseñaInput = $_POST['contrasenia_u']; 

        //usuario y su tipo
        $stmt = $conn->prepare("SELECT contrasenia_u, tipo_u FROM usuario WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usernameInput);
        $stmt->execute();

        //usuario existe
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $encryptedPassword = $row['contrasenia_u']; 
            $tipoUsuario = $row['tipo_u']; 

            // Desencriptar la contra
            $decryptedPassword = decryptPassword($encryptedPassword);

            // Verificar si la contraseña ingresada coincide con la desencriptada
            if ($contraseñaInput === $decryptedPassword) {
               
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