<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Bienvenido, <?= $_SESSION['nombre_usuario'] ?></h3>
    <p>Rol: <?= $_SESSION['rol'] ?></p>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Tu perfil</div>
        <div class="card-body">
            <p><strong>Nombre:</strong> <?= $usuario['nombre_usuario'] ?></p>
            <p><strong>Correo:</strong> <?= $usuario['correo'] ?></p>

            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-warning">Editar perfil</a>
            <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar tu cuenta?')">Eliminar cuenta</a>
            <a href="logout.php" class="btn btn-secondary float-end">Cerrar sesión</a>
        </div>
    </div>
</div>
</body>
</html>
