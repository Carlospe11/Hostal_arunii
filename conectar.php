<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'hoteell';
$username = 'root';
$password = 'carlos123AEI';

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);
// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar las credenciales del usuario
if (isset($_POST['n'], $_POST['p'])) {
    $nombre = $_POST['n'];
    $contraseña = $_POST['p'];

    // Preparar y ejecutar la consulta SQL
    $sql = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nombre, $contraseña);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Credenciales correctas, redirigir a revisar.php
        header('Location: revisar.php');
        exit();
    } else {
        // Credenciales incorrectas, mostrar mensaje de error
        echo "Nombre de usuario o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>