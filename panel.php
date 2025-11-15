<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$query = "SELECT nombre, transtorno FROM usuarios WHERE id_usuario='$id_usuario'";
$resultado = mysqli_query($conn, $query);
$usuario = mysqli_fetch_assoc($resultado);

// ✅ Si el usuario ya tiene un trastorno y NO viene desde el botón de cambiar, redirigir a principal.php
if (!empty($usuario['transtorno']) && !isset($_GET['cambiar'])) {
    header("Location: principal.php");
    exit();
}


// ✅ Si se envía el formulario para guardar el trastorno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transtorno = $_POST['transtorno'];
    $update = "UPDATE usuarios SET transtorno='$transtorno' WHERE id_usuario='$id_usuario'";
    mysqli_query($conn, $update);
    $usuario['transtorno'] = $transtorno;

    // ✅ Después de guardar, redirigir a la página principal
    header("Location: principal.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Usuario</title>
    <style>
        body {
            font-family: 'Comic Sans MS', sans-serif;
            background: linear-gradient(to bottom right, #a8e6ff, #f4e1ff);
            text-align: center;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .panel {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            padding: 30px;
            width: 80%;
            max-width: 500px;
            margin: 80px auto;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #aaa;
            margin-top: 15px;
        }
        button {
            background-color: #5aa9e6;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color: #3d8be4;
        }
    </style>
</head>
<body>

<div class="panel">
    <h1>👋 ¡Hola, <?php echo $usuario['nombre']; ?>!</h1>
    <p>Selecciona el trastorno que padeces o deseas gestionar:</p>

    <form method="POST">
        <select name="transtorno" required>
            <option value="">-- Selecciona una opción --</option>
            <option value="Sindrome de Down">Síndrome de Down</option>
            <option value="TDAH">TDAH</option>
            <option value="Dislexia">Dislexia</option>
            <option value="Autismo">Autismo</option>
            <option value="Sindrome de Aprendizaje">Síndrome de Aprendizaje</option>
            <option value="Estres Post Traumático">Estrés Post Traumático</option>
        </select>
        <br>
        <button type="submit">💾 Guardar Trastorno</button>
    </form>
</div>

</body>
</html>
