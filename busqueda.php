<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "carlos123AEI";
$dbname = "hoteell";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar la reserva
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los datos están presentes
    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['ci'], $_POST['telefono'], $_POST['habitacion_id'], $_POST['pago_porcentaje'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $ci = $_POST['ci'];
        $telefono = $_POST['telefono'];
        $habitacion_id = $_POST['habitacion_id'];
        $pago_porcentaje = $_POST['pago_porcentaje'];

        // Obtener información de la habitación
        $sql = "SELECT * FROM habitaciones WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $habitacion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $habitacion = $result->fetch_assoc();

        // Calcular el precio total y el pago
        $precio_total = $habitacion['precio'];
        $pago = $precio_total * ($pago_porcentaje / 100);

        // Insertar reserva
        $sql = "INSERT INTO reservas (habitacion_id, nombre, apellido, email, ci, telefono, pago_realizado, pago_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssddd", $habitacion_id, $nombre, $apellido, $email, $ci, $telefono, $pago, $precio_total);
        $stmt->execute();

        // Generar código QR
        $qr_code = generate_qr_code($pago);

        // Mostrar mensaje de éxito y código QR
        echo "<div class='text-center'>";
        echo "<h2>Reserva realizada con éxito</h2>";
        echo "<img src='$qr_code' alt='QR Code' />";
        echo "</div>";
    } else {
        echo "Error: Faltan datos necesarios.";
    }
}

// Función para generar código QR
function generate_qr_code($amount) {
    // Aquí iría el código para generar el QR basado en la cantidad a pagar
    // Por simplicidad, se devuelve un placeholder
    return "../img/qr.png";
}

$conn->close();
?>
