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

// Definir los nuevos valores
$nombre_viejo = 'nombre'; // Nombre del usuario actual
$nombre_nuevo = 'carlos_aruni'; // Nuevo nombre del usuario
$contrasena_nueva = 'carlos123AEI'; // Nueva contraseña del usuario

// Actualizar la contraseña del usuario
$sql = "UPDATE usuarios SET nombre = ?, contraseña = ? WHERE nombre = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("sss", $nombre_nuevo, $contrasena_nueva, $nombre_viejo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Nombre y contraseña actualizados correctamente.";
} else {
    echo "No se encontró el usuario o no se pudo actualizar.";
}

$stmt->close();
$conn->close();
?>