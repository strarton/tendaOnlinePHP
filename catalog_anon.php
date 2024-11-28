<?php
// Incluir la clase ManagerDB
require_once 'ManagerDB.php';

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'BDVdAWEB';
$username = 'root';
$password = '1234'; // Ajusta si tienes una contraseña

// Crear una instancia de ManagerDB
$database = new ManagerDB($host, $dbname, $username, $password);

// Obtener conexión PDO
$pdo = $database->getPDO();

// Consulta para obtener productos de la base de datos
$query = "SELECT p.nom AS nombre, p.descripcio AS descripcion, p.preu AS precio, c.nom AS categoria 
          FROM producte p
          LEFT JOIN format f ON f.id_producte = p.id
          LEFT JOIN categoria c ON f.id_categoria = c.id";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Obtener los resultados
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo Anónimo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .producto {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .producto h2 {
            margin: 0 0 10px;
        }
        .producto p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Catálogo de Productos</h1>
<p>Para comprar productos porfavor:</p>
<a href="login.php">Iniciar Sesión</a>
        <a href="register.php">Registrarse</a>
    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
            <div class="producto">
                <h2><?= htmlspecialchars($producto['nombre']); ?></h2>
                <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['descripcion'] ?? 'Sin descripción'); ?></p>
                <p><strong>Categoría:</strong> <?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría'); ?></p>
                <p><strong>Precio:</strong> €<?= htmlspecialchars(number_format($producto['precio'], 2)); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay productos disponibles en este momento.</p>
    <?php endif; ?>
</body>
</html>
