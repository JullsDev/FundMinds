<?php
session_start();
include 'conexion.php';

// Seguridad admin
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verificar que venga el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = $_GET['id'];

// Consulta segura
$sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Verificar que exista el usuario
if (mysqli_num_rows($resultado) !== 1) {
    header("Location: admin.php");
    exit();
}

$usuario = mysqli_fetch_assoc($resultado);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="form-container">

    <h2>Editar Usuario</h2>

    <form action="actualizar_usuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

        <label>Correo</label>
        <input type="email" name="correo" value="<?php echo $usuario['correo']; ?>" required>

        <label>Rol</label>
        <select name="tipo_usuario">
            <option value="admin" <?php if($usuario['tipo_usuario']=='admin') echo 'selected'; ?>>Admin</option>
            <option value="estudiante" <?php if($usuario['tipo_usuario']=='estudiante') echo 'selected'; ?>>Estudiante</option>
            <option value="acudiente" <?php if($usuario['tipo_usuario']=='acudiente') echo 'selected'; ?>>Acudiente</option>
            <option value="profesional" <?php if($usuario['tipo_usuario']=='profesional') echo 'selected'; ?>>Profesional</option>
        </select>

        <div class="form-actions">
            <button type="submit" class="btn-save">Guardar cambios</button>

            <!-- BOTÓN ELIMINAR -->
            <a href="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>"
               class="btn-delete"
               onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
               Eliminar
            </a>
        </div>
    </form>

    <a href="admin.php" class="btn-back">⬅ Volver al panel</a>

</div>
</body>
</html>
