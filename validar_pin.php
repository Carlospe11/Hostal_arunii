<?php
session_start();

// PIN correcto
$correct_pin = "1010"; // Cambia esto al PIN que desees

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pin = $_POST['pin'];

    if ($pin === $correct_pin) {
        // Redirigir al formulario de inicio de sesión
        header('Location: login.html');
        exit();
    } else {
        // Mostrar mensaje de error
        echo "PIN incorrecto. Por favor, intente nuevamente.";
    }
}
?>