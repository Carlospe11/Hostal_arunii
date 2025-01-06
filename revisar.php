
<?php
session_start();
include 'db.php';


$sql = "SELECT * FROM reservas ORDER BY fecha_ingreso DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostal Aruni - Revisión de Reservas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #E67E22;
            --accent-color: #3498DB;
            --background-color: rgba(255, 255, 255, 0.95);
            --text-color: #2C3E50;
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            --table-header-bg: #34495E;
            --table-stripe: #F8F9FA;
            --table-hover: #E9ECEF;
        }

        body {
    font-family: 'Open Sans', sans-serif;
    background-image: url('img/cara.jpeg');
    background-size: cover; /* Ajusta la imagen para que cubra toda la pantalla */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
    margin: 0; /* Elimina cualquier margen predeterminado */
    min-height: 100vh; /* Asegura que el cuerpo de la página tenga al menos la altura de la ventana gráfica */
    color: var(--text-color);
    line-height: 1.6;
}
        /* Header styles - preserved but slightly enhanced */
        header {
            background-color: #000 ;
    color: #fff;
    padding-top: 1rem 0;
    min-height: 50px;
    border-bottom: 5px solid black;
        }

        .contenedor {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            height: 100px;
      width: auto;
      float: right;
      border-radius: 100%;     width: 80px;
            height: auto;
        }

        .marca h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .marca h1 span {
            display: inline-block;
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .marca h1 span:hover {
            transform: translateY(-5px);
            color: var(--secondary-color);
        }

        .marca h4 {
            color: var(--secondary-color);
            margin: 0.5rem 0;
            font-size: 1.2rem;
        }

        .boton {
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 600;
        }

        .boton:hover {
            background-color: var(--secondary-color);
        }

        /* Main content styles */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .content-wrapper {
            background-color: var(--background-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 700;
        }

        /* Table styles */
        .table-container {
            overflow-x: auto;
            margin: 1rem 0;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            font-size: 0.95rem;
        }

        th {
            background-color: var(--table-header-bg);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: var(--table-stripe);
        }

        tr:hover {
            background-color: var(--table-hover);
        }

        /* Email cell specific styling */
        .email-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .email-cell:hover {
            overflow: visible;
            white-space: normal;
            background-color: white;
            position: relative;
            z-index: 1;
            box-shadow: var(--box-shadow);
            border-radius: 4px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .contenedor {
                flex-direction: column;
                text-align: center;
                padding: 1rem;
            }

            .marca h1 {
                font-size: 2rem;
            }

            .content-wrapper {
                padding: 1rem;
            }

            td, th {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
        }

        /* Message styles */
        .message {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin: 1rem 0;
            text-align: center;
            background-color: #F8D7DA;
            color: #721C24;
        }
    </style>
</head>
<body>
    <header>
        <div class="contenedor">
            <img class="logo" src="img/logo.jpeg" alt="Hostal Aruni Logo">
            <div class="marca">
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
                <h4>"Bienvenido a tu segundo hogar"</h4>
            </div>
            <nav>
                <button class="boton" onclick="window.location.href='index.html';">INICIO</button>
            </nav>
        </div>
    </header>


    <div class="container">
        <div class="content-wrapper">
            <h1>Reservas Registradas</h1>
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Habitación</th>
                                <th>Ingreso</th>
                                <th>Salida</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>CI</th>
                                <th>Teléfono</th>
                                <th>Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['habitacion_id']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['fecha_ingreso'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['fecha_salida'])) ?></td>
                                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                                    <td><?= htmlspecialchars($row['apellido']) ?></td>
                                    <td class="email-cell"><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['ci']) ?></td>
                                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                                    <td><?= htmlspecialchars($row['pagoPorcentaje']) ?>%</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="message">No hay reservas disponibles.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>