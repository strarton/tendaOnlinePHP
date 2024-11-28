<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['user'])) {
    echo "Debes iniciar sesión para ver el catálogo de productos.";
    exit;
}

// Conectar a la base de datos
require_once 'ManagerDB.php';

try {
    // Inicializar la conexión con la base de datos
    $dbManager = new ManagerDB("localhost", "BDVdAWEB", "root", "1234");
    $pdo = $dbManager->getPDO();

    // Consulta para obtener todos los productos
    $query = "SELECT * FROM producte";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Obtener todos los productos
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error al conectar con la base de datos: " . $e->getMessage();
    exit;
}

// Manejo de añadir al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Si el carrito no está creado aún en la sesión, lo inicializamos
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Si el producto ya está en el carrito, aumentamos la cantidad
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Si no está en el carrito, lo añadimos
        $_SESSION['cart'][$productId] = [
            'quantity' => $quantity
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
</head>
<body>
    <h1>Bienvenido al Catálogo de Productos, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>

    <h2>Lista de Productos</h2>
    
    <?php if (empty($productos)): ?>
        <p>No hay productos disponibles.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($productos as $producto): ?>
                <li>
                    <h3><?= htmlspecialchars($producto['nom']) ?></h3>
                    <p><?= htmlspecialchars($producto['descripcio']) ?></p>
                    <p>Precio: $<?= htmlspecialchars($producto['preu']) ?></p>
                    <?php if (!empty($producto['foto'])): ?>
                        <img src="<?= htmlspecialchars($producto['foto']) ?>" alt="<?= htmlspecialchars($producto['nom']) ?>" width="100">
                    <?php endif; ?>
                    
                    <!-- Formulario para añadir al carrito -->
                    <form method="POST" action="catalogo.php">
                        <input type="hidden" name="product_id" value="<?= $producto['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" required>
                        <button type="submit" name="add_to_cart">Añadir al carrito</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Enlace para ir al carrito -->
    <a href="carrito.php">Ver mi carrito</a>

    <!-- Enlace para cerrar sesión -->
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
