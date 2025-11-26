<?php
session_start();
include 'conexion.php';

// Seguridad: solo admin
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id_usuario = $_POST['id_usuario'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$tipo_usuario = $_POST['tipo_usuario'];

// Evitar que el admin cambie su propio rol
if ($id_usuario == $_SESSION['id_usuario']) {
    header("Location: admin.php");
    exit();
}

// Seguridad extra
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['nombre'])) {
    die("Sesión de administrador no válida");
}

// Pasar datos del admin al trigger
$admin_id = (int) $_SESSION['id_usuario'];
$admin_nombre = mysqli_real_escape_string($conn, $_SESSION['nombre']);

mysqli_query($conn, "SET @admin_id = $admin_id");
mysqli_query($conn, "SET @admin_nombre = '$admin_nombre'");


$query = "UPDATE usuarios 
          SET nombre='$nombre', correo='$correo', tipo_usuario='$tipo_usuario'
          WHERE id_usuario=$id_usuario";

mysqli_query($conn, $query);

header("Location: admin.php");
exit();
