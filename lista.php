<?php
$servername = "localhost";
$username = "root";
$password = "carlos123AEI";
$dbname = "hoteell";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM habitaciones";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostal Aruni - Habitaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=2574&auto=format&fit=crop');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            min-height: 100vh;
        }

        .glass-header {
            background: rgba(2, 2, 2, 0.8);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(182, 173, 173, 0.2);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgb(255, 255, 255);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: translateY(-5px);
        }

        .room-image {
            transition: transform 0.3s ease;
        }
        
        .text-3xl{
            background-color=000;

        }
        .room-image:hover {
            transform: scale(1.05);
        }

        .category-title {
            text-shadow: 2px 2px 4px rgb(255, 255, 255);
            letter-spacing: 0.1em;
        }

        main {
            padding: 2rem 1rem;
        }

        @media (min-width: 768px) {
            main {
                padding: 4rem 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="glass-header sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <img src="img/logo.jpeg" alt="Hostal Aruni" class="h-16 w-auto rounded-lg shadow-lg">
                    <div>
                        <h1 class="text-3xl font-bold tracking-wider text-gray-900 texto-blanco">
                            HOSTAL ARUNI
                        </h1>
                        <p class="text-gray-700 italic font-medium">
                            "Tenemos las siguientes habitaciones:"
                        </p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="index.html" class="px-6 py-2 rounded-full bg-white/30 hover:bg-white/50 text-gray-800 font-medium transition-all">
                        VOLVER
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container mx-auto max-w-7xl">
        <?php
        if ($result->num_rows > 0) {
            $count = 0;
            $categories = ['PAQUETE CON VENTILADOR', 'PAQUETE CON AIRE ACONDICIONADO'];
            
            foreach ($categories as $category) {
                echo '<div class="mb-20">';
                echo '<h2 class="category-title text-4xl font-bold text-center mb-12 text-gray-900">' . $category . '</h2>';
                echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-4">';
                
                mysqli_data_seek($result, $count);
                for ($i = 0; $i < 4 && $row = $result->fetch_assoc(); $i++) {
                    $camas = $row['cama_plazaymedia'] + $row['cama_matrimonial'];
                    $servicios = explode(",", $row['servicios']);
                    $imagen = 'H' . $row['numero_habitacion'] . '.jpg';
                    ?>
                    <div class="glass-card rounded-2xl overflow-hidden shadow-xl">
                        <div class="relative overflow-hidden">
                            <img src="img/<?= $imagen ?>" alt="<?= $row['tipo_habitacion'] ?>" 
                                 class="w-full h-72 object-cover room-image">
                            <span class="absolute top-4 right-4 px-4 py-1.5 bg-green-500 text-white text-sm font-medium rounded-full shadow-lg">
                                Disponible
                            </span>
                        </div>
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900"><?= $row['tipo_habitacion'] ?></h3>
                                    <p class="text-gray-700 mt-1">
                                        <span class="font-medium"><?= $row['capacidad'] ?> personas</span>
                                        <span class="mx-2">•</span>
                                        <span class="font-medium"><?= $camas ?> camas</span>
                                    </p>
                                </div>
                                <span class="px-4 py-1.5 bg-gray-100/80 text-gray-700 rounded-full text-sm font-medium">
                                    <?= $row['ventiladora'] ?>
                                </span>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-gray-700 flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?= $row['descripcion'] ?>
                                    <button onclick="alert('Niños mayor de 13 años cuenta como adulto y paga tarifa completa')"
                                            class="ml-2 text-blue-600 hover:text-blue-700 underline font-medium">
                                        Más información
                                    </button>
                                </p>
                            </div>

                            <div class="mb-8">
                                <h4 class="font-bold text-gray-900 mb-4">Servicios:</h4>
                                <ul class="grid grid-cols-2 gap-3">
                                    <?php foreach ($servicios as $servicio): ?>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <?= trim($servicio) ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-200/50">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Precio por noche</p>
                                    <p class="text-3xl font-bold text-gray-900">Bs. <?= $row['precio'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count++;
                }
                echo '</div></div>';
            }
        }
        $conn->close();
        ?>
    </main>

    <footer class="bg-gray-900/80 backdrop-blur-sm text-gray-400 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="font-medium">&copy; <?= date('Y') ?> Hostal Aruni. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>