<?php
session_start();
include 'conexion.php';

// Verificar si hay sesión activa
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$mensaje = "";

// Si el usuario confirma la eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        $mensaje = "Error al eliminar la cuenta: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4">Eliminar Cuenta</h3>

        <div class="alert alert-danger">
            <strong>¡Atención!</strong> ¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no puede deshacerse.
        </div>

        <form method="POST">
            <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
            <a href="usuario_dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>

        <?php if ($mensaje != ""): ?>
            <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
