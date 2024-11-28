<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['user'])) {
    echo "Debes iniciar sesión para ver el carrito.";
    exit;
}

// Verificar si hay productos en el carrito
if (empty($_SESSION['cart'])) {
    echo "Tu carrito está vacío.";
    exit;
}

// Conectar a la base de datos
require_once 'ManagerDB.php';

try {
    // Inicializar la conexión con la base de datos
    $dbManager = new ManagerDB("localhost", "BDVdAWEB", "root", "1234");
    $pdo = $dbManager->getPDO();

    // Obtener los productos del carrito
    $cartItems = [];
    foreach ($_SESSION['cart'] as $productId => $item) {
        $query = "SELECT * FROM producte WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
        }
    }
} catch (Exception $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito</title>
</head>
<body>
    <h1>Carrito de Compras</h1>

    <?php if (empty($cartItems)): ?>
        <p>No hay productos en tu carrito.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <h3><?= htmlspecialchars($item['product']['nom']) ?></h3>
                    <p>Descripción: <?= htmlspecialchars($item['product']['descripcio']) ?></p>
                    <p>Cantidad: <?= htmlspecialchars($item['quantity']) ?></p>
                    <p>Precio por unidad: $<?= htmlspecialchars($item['product']['preu']) ?></p>
                    <p>Total: $<?= htmlspecialchars($item['product']['preu'] * $item['quantity']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="catalogo.php">Volver al catálogo</a>

    <!-- Enlace para cerrar sesión -->
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
