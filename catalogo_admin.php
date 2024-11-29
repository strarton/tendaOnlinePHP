<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'ManagerDB.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$dbManager = new ManagerDB("localhost", "BDVdAWEB", "root", "1234");

// Obtener todos los productos
try {
    $productos = $dbManager->getAllProducts();
} catch (Exception $e) {
    die("Error al obtener los productos: " . $e->getMessage());
}

// Eliminar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $idProducto = $_POST['id_producto'] ?? null;
    if ($idProducto) {
        try {
            $dbManager->deleteProduct($idProducto);
            header("Location: catalogo_admin.php");
            exit;
        } catch (Exception $e) {
            $error = "Error al eliminar el producto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Catálogo</title>
</head>
<body>
    <h1>Catálogo de Productos - Admin</h1>
    <a href="logout.php">Cerrar sesión</a>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Unidades</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['id']) ?></td>
                    <td><?= htmlspecialchars($producto['nom']) ?></td>
                    <td><?= htmlspecialchars($producto['n_unitats']) ?></td>
                    <td><?= htmlspecialchars($producto['preu']) ?></td>
                    <td><?= htmlspecialchars($producto['descripcio']) ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?= $producto['id'] ?>">Editar</a>
                        <form action="catalogo_admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                            <button type="submit" name="delete" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
