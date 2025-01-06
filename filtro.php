<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostal Aruni - Habitaciones Disponibles</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f8fd 0%, #f1f4f9 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(221, 211, 211, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .room-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .logo-animation span {
            display: inline-block;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        header {
            background-color: #000;
            color: #fff;
            padding: 1rem 0;
            border-bottom: 5px solid black;
        }

        .contenedor {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            height: 80px;
            width: auto;
            border-radius: 50%;
        }

        .marca h1 {
            font-size: 2rem;
            margin: 0;
        }

        .marca h4 {
            margin: 0;
            color: #E67E22;
        }

        .boton {
            background-color: #E67E22;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .boton:hover {
            background-color: #D35400;
        }

        .results-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="glass-effect sticky top-0 z-50 shadow-lg">
        <div class="contenedor">
            <img src="img/logo.jpeg" alt="Hostal Aruni" class="logo">
            <div class="marca">
                <h1 class="logo-animation">
                    <?php
                    $letters = str_split("HOSTAL ARUNI");
                    foreach ($letters as $index => $letter) {
                        echo "<span style='animation-delay: {$index}0ms'>$letter</span>";
                    }
                    ?>
                </h1>
                <h4>"Bienvenido a tu segundo hogar"</h4>
            </div>
            <nav>
                <a href="index.html" class="boton">Volver</a>
                <a href="nosotros.html" class="boton">Nosotros</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="results-container">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "carlos123AEI";
        $dbname = "hoteell";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Recibir y validar datos del formulario
        $adultos = is_numeric($_POST['adultos']) ? intval($_POST['adultos']) : 0;
        $menores = is_numeric($_POST['menores']) ? intval($_POST['menores']) : 0;
        $total_personas = $adultos + $menores;
        $ventilador = $_POST['acondicionador'];
        $fechaIngreso = $_POST['fecha_entrada'];
        $fechaSalida = $_POST['fecha_salida'];

        // Consulta SQL (misma que antes)
        $sql = "SELECT h.*, 
                (h.cantidad - COALESCE(
                    (SELECT COUNT(*) 
                     FROM reservas r 
                     WHERE r.habitacion_id = h.id 
                     AND (
                         ('$fechaIngreso' BETWEEN r.fecha_ingreso AND r.fecha_salida)
                         OR ('$fechaSalida' BETWEEN r.fecha_ingreso AND r.fecha_salida)
                         OR (r.fecha_ingreso BETWEEN '$fechaIngreso' AND '$fechaSalida')
                         OR (r.fecha_salida BETWEEN '$fechaIngreso' AND '$fechaSalida')
                     )
                    ), 0
                )) as habitaciones_disponibles
                FROM habitaciones h
                WHERE h.ventiladora = ?
                AND h.capacidad >= ?
                HAVING habitaciones_disponibles > 0
                ORDER BY 
                CASE 
                    WHEN $total_personas <= 2 THEN h.capacidad = 2
                    WHEN $total_personas = 3 THEN h.capacidad = 3
                    WHEN $total_personas >= 4 THEN h.capacidad >= 4
                END DESC,
                h.precio ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $ventilador, $total_personas);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';
            
            while ($row = $result->fetch_assoc()) {
                $camas = $row['cama_plazaymedia'] + $row['cama_matrimonial'];
                $servicios = explode(",", $row['servicios']);
                $imagen = 'H'.$row['numero_habitacion'].'.jpg';
                $reservaId = 'form-' . $row['id'];
        ?>
                <div class="room-card glass-effect rounded-xl overflow-hidden">
                    <img src="img/<?= $imagen ?>" alt="<?= $row['tipo_habitacion'] ?>" 
                         class="w-full h-64 object-cover">
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">
                                    <?= $row['tipo_habitacion'] ?>
                                </h2>
                                <p class="text-sm text-gray-600">
                                    <?= $row['ventiladora'] ?> | 
                                    Habitaciones disponibles: <?= $row['habitaciones_disponibles'] ?>
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                Disponible
                            </span>
                        </div>

                        <div class="space-y-3 mb-4">
                            <p class="flex items-center text-gray-600">
                                <i class='bx bx-user mr-2'></i>
                                Capacidad: <?= $row['capacidad'] ?> personas
                            </p>
                            <p class="flex items-center text-gray-600">
                                <i class='bx bx-bed mr-2'></i>
                                <?= $camas ?> camas
                            </p>
                            <div class="text-sm text-gray-500">
                                <?= $row['descripcion'] ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="font-semibold mb-2">Servicios:</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <?php foreach ($servicios as $servicio): ?>
                                    <span class="flex items-center text-sm text-gray-600">
                                        <i class='bx bx-check mr-2 text-green-500'></i>
                                        <?= trim($servicio) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <p class="text-2xl font-bold text-blue-600">
                                Bs. <?= number_format($row['precio'], 2) ?>
                            </p>
                            <button onclick="document.getElementById('<?= $reservaId ?>').classList.toggle('hidden')"
                                    class="boton">
                                Reservar
                            </button>
                        </div>

                        <!-- Formulario de Reserva -->
                        <div id="<?= $reservaId ?>" class="hidden mt-6">
                            <form action="reserva.php" method="post" class="space-y-4">
                                <input type="hidden" name="habitacion_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="adultos" value="<?= $adultos ?>">
                                <input type="hidden" name="menores" value="<?= $menores ?>">
                                <input type="hidden" name="acondicionador" value="<?= $ventilador ?>">
                                <input type="hidden" name="fecha_entrada" value="<?= $fechaIngreso ?>">
                                <input type="hidden" name="fecha_salida" value="<?= $fechaSalida ?>">
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="nombre" required placeholder="Nombre"
                                           class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-400">
                                    <input type="text" name="apellido" required placeholder="Apellido"
                                           class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-400">
                                </div>

                                <input type="email" name="email" required placeholder="Email"
                                       class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-400">
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="ci" required placeholder="CI"
                                           class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-400">
                                    <input type="tel" name="telefono" required placeholder="Teléfono"
                                           class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-400">
                                </div>

                                <button type="submit" name="pago_porcentaje" value="50"
                                        class="w-full py-3 boton">
                                    Confirmar Reserva
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
        <?php
            }
            echo '</div>';
        } else {
            echo '<div class="flex justify-center items-center min-h-[60vh]">
                    <div class="text-center p-8 glass-effect rounded-xl max-w-md">
                        <i class="bx bx-search-alt text-6xl text-gray-400 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">No hay habitaciones disponibles</h2>
                        <p class="text-gray-600 mb-6">Lo sentimos, no encontramos habitaciones que coincidan con tus criterios de búsqueda.</p>
                        <a href="index.html" class="boton">
                            Volver a buscar
                        </a>
                    </div>
                  </div>';
        }
        $conn->close();
        ?>
    </main>

    <!-- Footer -->
    <footer class="glass-effect mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-gray-600">
            <p>&copy; <?= date('Y') ?> Hostal Aruni. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>