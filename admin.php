<?php
session_start();
include 'conexion.php';

// Seguridad: solo admin
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener usuarios
$usuarios = mysqli_query($conn, "SELECT id_usuario, nombre, correo, tipo_usuario FROM usuarios");

// Obtener comentarios de profesionales
$query = "SELECT c.id_comentario, c.mensaje, c.fecha, u.nombre
          FROM comentarios c
          INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
          ORDER BY c.fecha DESC";

$resultado = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador | Fund Minds</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="container">

    <div class="top-bar">
        <div>
            <h1>Panel de Administración</h1>
            <h2>Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>
        </div>
        <a href="logout.php" class="logout">Cerrar sesión</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($fila = mysqli_fetch_assoc($usuarios)) { ?>
            <tr>
                <td><?php echo $fila['id_usuario']; ?></td>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['correo']; ?></td>
                <td>
                    <span class="rol <?php echo $fila['tipo_usuario']; ?>">
                        <?php echo ucfirst($fila['tipo_usuario']); ?>
                    </span>
                </td>
                <td>
                <?php if ($fila['id_usuario'] != $_SESSION['id_usuario']) { ?>
                    <a class="btn" href="editar_usuario.php?id=<?php echo $fila['id_usuario']; ?>">
                        Editar
                    </a>
                <?php } else { ?>
                    <span style="color: gray;">No disponible</span>
                <?php } ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

</div>

<h2>💬 Comentarios de Profesionales</h2>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr>
        <th>Profesional</th>
        <th>Comentario</th>
        <th>Fecha</th>
        <th>Acción</th>
    </tr>

<?php while ($comentario = mysqli_fetch_assoc($resultado)) { ?>
    <tr>
        <td><?php echo $comentario['nombre']; ?></td>
        <td><?php echo $comentario['mensaje']; ?></td>
        <td><?php echo $comentario['fecha']; ?></td>
        <td>
            <a href="eliminar_comentario.php?id=<?php echo $comentario['id_comentario']; ?>"
               onclick="return confirm('¿Eliminar comentario?')">
               🗑 Eliminar
            </a>
        </td>
    </tr>
<?php } ?>

</table>

</body>
</html>
