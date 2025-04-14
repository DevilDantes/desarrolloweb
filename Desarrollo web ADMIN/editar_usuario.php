<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$error = "";

$id = (int)$_SESSION['id'];

// Obtener los datos del usuario logueado
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    if (!empty($nombre_usuario) && !empty($correo)) {
        if (!empty($contrasena)) {
            if (strlen($contrasena) < 6) {
                $error = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            }
        } else {
            // Si no cambia la contraseña, se mantiene la actual
            $contrasena_hash = $usuario['contrasena'];
        }

        if (empty($error)) {
            $sql = "UPDATE usuarios SET nombre_usuario = ?, correo = ?, contrasena = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nombre_usuario, $correo, $contrasena_hash, $id);

            if ($stmt->execute()) {
                $mensaje = "Perfil actualizado exitosamente.";
                // Actualiza el nombre en la sesión si fue modificado
                $_SESSION['nombre_usuario'] = $nombre_usuario;
            } else {
                $error = "Error al actualizar: " . $conn->error;
            }
        }
    } else {
        $error = "El nombre de usuario y correo son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4">Editar Perfil</h3>

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
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña (dejar en blanco si no deseas cambiarla)</label>
                <input type="password" name="contrasena" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>

        <a href="usuario_dashboard.php" class="btn btn-secondary mt-3">Volver al perfil</a>
    </div>
</body>
</html>
