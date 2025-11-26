<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"]; 
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    $tipo_usuario = $_POST["tipo_usuario"];

    // ✅ Encriptar contraseña antes de guardarla
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, apellido, correo, contrasena, tipo_usuario)
            VALUES ('$nombre', '$apellido', '$correo', '$hash', '$tipo_usuario')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Usuario registrado correctamente'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Fund Minds</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #A1C4FD, #C2E9FB);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .registro-container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        h2 { color: #4A90E2; }
        input, select {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
        button {
            background: #4A90E2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #357ABD; }
        .volver {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #FFD166;
            color: black;
            padding: 8px 15px;
            border-radius: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="index.php" class="volver">⬅ Volver al inicio</a>
    <div class="registro-container">
        <h2>Crear cuenta</h2>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required><br>
            <input type="text" name="apellido" placeholder="Apellido" required><br>
            <input type="email" name="correo" placeholder="Correo electrónico" required><br>
            <input type="password" name="contrasena" placeholder="Contraseña" required><br>
            <select name="tipo_usuario" required>
                <option value="">Selecciona tipo de usuario</option>
                <option value="niño">Estudiante</option>
                <option value="acudiente">Acudiente</option>
                <option value="admin">Administrador</option>
                <option value="profesional">Profesional</option>
            </select><br>
            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
