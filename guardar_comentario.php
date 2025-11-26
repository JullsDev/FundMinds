<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'profesional') {
    header("Location: login.php");
    exit();
}

$mensaje = trim($_POST['mensaje']);
$id_usuario = $_SESSION['id_usuario'];

if (!empty($mensaje)) {
    $query = "INSERT INTO comentarios (id_usuario, mensaje)
              VALUES ('$id_usuario', '$mensaje')";
    mysqli_query($conn, $query);
}

header("Location: principal.php");
exit();
