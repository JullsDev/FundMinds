<?php
session_start();
include 'conexion.php';

// Seguridad admin
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: admin.php");
exit();
?>
