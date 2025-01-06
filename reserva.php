<?php
session_start(); // Start session to store search parameters

// Store the previous search parameters if they're coming from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['last_search'] = [
        'adultos' => $_POST['adultos'] ?? '',
        'menores' => $_POST['menores'] ?? '',
        'acondicionador' => $_POST['acondicionador'] ?? '',
        'fecha_entrada' => $_POST['fecha_entrada'] ?? '',
        'fecha_salida' => $_POST['fecha_salida'] ?? ''
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostal Aruni - Reservación</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #E67E22;
            --background-color: rgba(255, 255, 255, 0.95);
            --text-color: #2C3E50;
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            --whatsapp-color: #25D366;
            --whatsapp-hover: #128C7E;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-image: url('img/hostal.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            min-height: 100vh;
            color: var(--text-color);
        }

        /* Header styles - preserved as requested */
        header {
            background-color: #000 ;
    color: #fff;
    padding-top:1rem 0;
    min-height: 50px;
    border-bottom: 5px solid black;
           
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            height: 100px;
      width: auto;
      float: right;
      border-radius: 100%;
        }

        .brand {
            text-align: center;
        }

        .brand h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .brand h1 span {
            display: inline-block;
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .brand h1 span:hover {
            transform: translateY(-5px);
            color: var(--secondary-color);
        }

        .brand h4 {
            color: var(--secondary-color);
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }

        /* Content styles */
        .content {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: var(--background-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
        }

        .content h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }

        /* QR Code styles */
        .qr-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        .qr-image {
            width: 200px;
            height: 200px;
            border-radius: var(--border-radius);
            cursor: zoom-in;
            transition: transform 0.3s ease;
            box-shadow: var(--box-shadow);
        }

        .qr-image:hover {
            transform: scale(1.05);
        }

        /* Button styles */
        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .button {
            padding: 0.8rem 1.5rem;
            border-radius: var(--border-radius);
            border: none;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .primary-button {
            background-color: var(--primary-color);
            color: white;
        }

        .primary-button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .whatsapp-button {
            background-color: var(--whatsapp-color);
            color: white;
        }

        .whatsapp-button:hover {
            background-color: var(--whatsapp-hover);
            transform: translateY(-2px);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }

        .modal-content img {
            max-width: 400px;
            height: auto;
            border-radius: var(--border-radius);
            cursor: zoom-out;
        }

        .close-modal {
            position: absolute;
            top: -40px;
            right: -40px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            padding: 10px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                padding: 1rem;
            }

            .brand h1 {
                font-size: 2rem;
            }

            .content {
                margin: 1rem;
                padding: 1rem;
            }

            .button-container {
                flex-direction: column;
            }

            .button {
                width: 100%;
            }

            .modal-content img {
                max-width: 90%;
            }
        }

        /* Message styles */
        .message {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin: 1rem 0;
            text-align: center;
        }

        .error {
            background-color: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }

        .success {
            background-color: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <img class="logo" src="img/logo.jpeg" alt="Hostal Aruni Logo">
            <div class="brand">
                <h1>
                    <?php
                    $letters = str_split("HOSTAL ARUNI");
                    foreach ($letters as $letter) {
                        if ($letter === " ") {
                            echo "<span>&nbsp;</span>";
                        } else {
                            echo "<span>$letter</span>";
                        }
                    }
                    ?>
                </h1>
                <h4>"¡Solo falta pagar el 50% para reservar tu habitación!"</h4>
            </div>
            <nav>
                
                <button class="button primary-button" onclick="window.location.href='index.html';">VOLVER</button>
            </nav>
        </div>
    </header>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "carlos123AEI";
    $dbname = "hoteell";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo "<div class='content'><div class='message error'>Error de conexión: " . $conn->connect_error . "</div></div>";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        $ci = $_POST['ci'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $habitacion_id = intval($_POST['habitacion_id'] ?? 0);
        $pago_porcentaje = $_POST['pago_porcentaje'] ?? '';
        $fechaIngreso = $_POST['fechaIngreso'] ?? '';
        $fechaSalida = $_POST['fechaSalida'] ?? '';

        if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($ci) && 
            !empty($telefono) && $habitacion_id > 0 && !empty($pago_porcentaje) && 
            !empty($fechaIngreso) && !empty($fechaSalida)) {

            $dateIngreso = DateTime::createFromFormat('Y-m-d', $fechaIngreso);
            $dateSalida = DateTime::createFromFormat('Y-m-d', $fechaSalida);

            if ($dateIngreso && $dateSalida) {
                $fechaIngreso = $dateIngreso->format('Y-m-d');
                $fechaSalida = $dateSalida->format('Y-m-d');

                $sql = "INSERT INTO reservas (habitacion_id, fecha_ingreso, fecha_salida, nombre, apellido, email, ci, telefono, pagoPorcentaje) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issssiiis", $habitacion_id, $fechaIngreso, $fechaSalida, $nombre, $apellido, $email, $ci, $telefono, $pago_porcentaje);

                if ($stmt->execute()) {
                    echo "<div class='content'>
                            <h1>Escanea el código QR para realizar el pago</h1>
                            <div class='qr-container'>
                                <img src='img/qrr.jpg' alt='Código QR de pago' class='qr-image' onclick='openModal()'>
                                <div class='button-container'>
                                    <a href='img/qrr.jpg' download='codigo_qr_pago.jpg' class='button primary-button'>
                                        Descargar QR
                                    </a>
                                    <a href='https://wa.me/71461131?text=Hola%2C%20quiero%20consultar%20sobre%20mi%20reserva' 
                                       class='button whatsapp-button' target='_blank'>
                                       Consultar por WhatsApp
                                    </a>
                                </div>
                            </div>
                          </div>";
                } else {
                    echo "<div class='content'><div class='message error'>Error al procesar la reserva: " . $conn->error . "</div></div>";
                }
            } else {
                echo "<div class='content'><div class='message error'>Error: Formato de fecha incorrecto.</div></div>";
            }
        } else {
            echo "<div class='content'><div class='message error'>Error: Todos los campos son requeridos.</div></div>";
        }
    }

    $conn->close();
    ?>

    <!-- Modal for QR zoom -->
    <div id="qrModal" class="modal" onclick="closeModal()">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <img src="img/qrr.jpg" alt="Código QR de pago ampliado">
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('qrModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('qrModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>