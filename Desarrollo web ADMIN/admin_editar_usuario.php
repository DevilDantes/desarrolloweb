<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$id_usuario = $_GET['id'];
$mensaje = "";
$error = "";

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    die("Usuario no encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $contrasena = $_POST['contrasena'];

    if (!empty($nombre) && !empty($correo) && !empty($rol)) {
        if (!empty($contrasena)) {
            if (strlen($contrasena) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            }
        } else {
            $contrasena_hash = $usuario['contrasena'];
        }

        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = ?, correo = ?, contrasena = ?, rol = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nombre, $correo, $contrasena_hash, $rol, $id_usuario);

            if ($stmt->execute()) {
                $mensaje = "Usuario actualizado exitosamente.";
            } else {
                $error = "Error al actualizar: " . $conn->error;
            }
        }
    } else {
        $error = "Todos los campos excepto la contraseña son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Editar Usuario (Admin)</h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= $mensaje ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="nombre_usuario" class="form-control" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-control" required>
                <option value="usuario" <?= $usuario['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña (dejar en blanco para no cambiarla)</label>
            <input type="password" name="contrasena" class="form-control">
        </div>

        <button class="btn btn-primary">Guardar Cambios</button>
        <a href="admin_panel.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
