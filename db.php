<?php
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Cambia por tu usuario
$password = "carlos123AEI"; // Cambia por tu contraseña
$dbname = "hoteell"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>