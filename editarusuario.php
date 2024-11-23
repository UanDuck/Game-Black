<?php

// Definir la clave secreta y el método de encriptación
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


// Conectar gb
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "gb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar id
if (isset($_GET['id_u'])) {
    $id_u = $_GET['id_u'];

   
    $sql = "SELECT * FROM usuario WHERE id_u = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_u);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Desencriptar 
        $row['contrasenia_u'] = decryptPassword($row['contrasenia_u']); 
    } else {
        echo "Registro no encontrado.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        

        $usuario = $_POST['usuario'];
        $nom_u = $_POST['nombre'];
        $ap_u = $_POST['apellidop'];
        $am_u = $_POST['apellidom'];
        $correo_u = $_POST['email'];
        
        // Encriptar la nueva contraseña antes de almacenarla
        if (!empty($_POST['contrasenia'])) {
            $contrasenia_u = encryptPassword($_POST['contrasenia']); // Encriptar la nueva contraseña
        } else {
            // Si no se cambia la contraseña, mantener la original
            $contrasenia_u = $row['contrasenia_u'];
        }

        $tipo_u = $_POST['tipo'];
        $telefono = $_POST['telefono'];


        $sql = "UPDATE usuario SET usuario=?, nom_u=?, ap_u=?, am_u=?, correo_u=?, contrasenia_u=?, tipo_u=?, telefono=? WHERE id_u=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $usuario, $nom_u, $ap_u, $am_u, $correo_u, $contrasenia_u, $tipo_u, $telefono, $id_u);

        if ($stmt->execute()) {
            echo "Registro actualizado correctamente.";
            header("Location: consultarusuario.php"); 
            exit();
        } else {
            echo "Error al actualizar el registro: " . $conn->error;
        }
    }
} else {
    echo "ID no proporcionado.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro Usuario</title>
    <link rel="icon" href="logo.jpg" type="image/png">
    <link rel="stylesheet" href="css/login.css">
</head>
<bod>

    
<div class="form-side" style="margin-top: 300px;"></div>
    <div class="container">
    <h2>Editar Registro Usuario</h2>
        <form method="POST" onsubmit="return validarFormulario()">
            <div class="input-container">
                <input type="text" name="usuario" id="usuario" value="<?php echo $row['usuario']; ?>" required placeholder=" ">
                <label>USUARIO:</label>
            </div>
            <div class="input-container">
                <input type="text" name="nombre" id="nombre" value="<?php echo $row['nom_u']; ?>" required placeholder=" ">
                <label>NOMBRE:</label>
            </div>
            <div class="input-container">
                <input type="text" name="apellidop" id="apellidop" value="<?php echo $row['ap_u']; ?>" required placeholder=" ">
                <label>APELLIDO PATERNO:</label>
            </div>
            <div class="input-container">
                <input type="text" name="apellidom" value="<?php echo $row['am_u']; ?>" required placeholder=" ">
                <label>APELLIDO MATERNO:</label>
            </div>
            <div class="input-container">
                <input type="email" name="email" id="email" value="<?php echo $row['correo_u']; ?>" required placeholder=" ">
                <label>EMAIL:</label>
            </div>
            <div class="input-container">
                <input type="password" name="contrasenia" id="contrasenia" value="<?php echo $row['contrasenia_u']; ?>" placeholder=" ">
                <label>CONTRASEÑA:</label>
            </div>

            <div class="input-container">
                <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" value="<?php echo $row['contrasenia_u']; ?>" placeholder=" ">
                <label>CONFIRMAR CONTRASEÑA:</label>
            </div>

            <div class="input-container">
                <select name="tipo" required>
                    <option value="user" <?php echo ($row['tipo_u'] == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($row['tipo_u'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
               
            </div>
            <div class="input-container">
                <input type="number" name="telefono" value="<?php echo $row['telefono']; ?>" required placeholder=" ">
                <label>TELEFONO:</label>
            </div>
            <input type="submit" value="ACTUALIZAR">
        </form>
    </div>
</div>

    <script src="script.js"></script>
</body>
</html>
