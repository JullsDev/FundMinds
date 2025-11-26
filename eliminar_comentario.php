<?php
session_start();
include 'conexion.php';

if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM comentarios WHERE id_comentario = $id");

header("Location: admin.php");
exit();
