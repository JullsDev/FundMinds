<?php
session_start();
include("conexion.php"); // define $conn
date_default_timezone_set('America/Bogota');

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo "Error: sesión no iniciada.";
    exit();
}
$id_usuario = intval($_SESSION['id_usuario']);

// Validar POST
if (!isset($_POST['id_actividad'])) {
    echo "Error: id_actividad faltante.";
    exit();
}
$id_actividad = intval($_POST['id_actividad']);

// Verificar que la actividad exista (para mantener integridad referencial)
$checkAct = "SELECT id_actividad FROM actividades WHERE id_actividad = $id_actividad LIMIT 1";
$rAct = mysqli_query($conn, $checkAct);
if (!$rAct || mysqli_num_rows($rAct) == 0) {
    echo "Error: actividad no válida.";
    exit();
}

// Evitar duplicados: si ya existe un registro para este usuario+actividad -> no insertar otra vez
$check = "SELECT id_progreso FROM progreso WHERE id_usuario = $id_usuario AND id_actividad = $id_actividad LIMIT 1";
$r = mysqli_query($conn, $check);
if ($r && mysqli_num_rows($r) > 0) {
    echo "OK: ya completada";
    exit();
}

// Generar puntaje y tiempo (ejemplo)
$puntaje = rand(70, 100);
$tiempo_empleado = rand(2, 12); // minutos
$fecha = date("Y-m-d H:i:s");

// Insertar en progreso
$insert = "INSERT INTO progreso (id_usuario, id_actividad, puntaje, tiempo_empleado, fecha_realizacion)
           VALUES ($id_usuario, $id_actividad, $puntaje, $tiempo_empleado, '$fecha')";

if (mysqli_query($conn, $insert)) {
    echo "OK";
} else {
    echo "Error al guardar: " . mysqli_error($conn);
}
?>
