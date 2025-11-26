<?php
ob_start(); // Evita errores al usar header()
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $contrasena = trim($_POST["contrasena"]);

    // Prepared statement para evitar SQL injection
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) == 1) {
            $usuario = mysqli_fetch_assoc($resultado);

            // Verificar contraseña (hash o texto plano)
            $stored = $usuario["contrasena"];

            $password_ok = false;
            if (password_verify($contrasena, $stored)) {
                $password_ok = true;
            } elseif ($contrasena === $stored) {
                // Mantener compatibilidad con contraseñas en texto plano (no recomendado)
                $password_ok = true;
            }

            if ($password_ok) {
                // Guardar datos relevantes en la sesión
                $_SESSION["id_usuario"]   = $usuario["id_usuario"];
                $_SESSION["nombre"]       = $usuario["nombre"];
                $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"]; // contiene 'admin' para administradores

                // Redirigir según rol (tu rol admin se llama 'admin')
                if (isset($usuario['tipo_usuario']) && $usuario['tipo_usuario'] === 'admin') {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: panel.php");
                    exit();
                }

            } else {
                echo "<script>alert('Contraseña incorrecta'); window.location='login.php';</script>";
                exit();
            }

        } else {
            echo "<script>alert('Usuario no encontrado'); window.location='login.php';</script>";
            exit();
        }

        mysqli_stmt_close($stmt);
    } else {
        // Error preparando la consulta
        echo "<script>alert('Error del servidor. Intente más tarde'); window.location='login.php';</script>";
        exit();
    }
}

ob_end_flush();
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión - Fund Minds</title>
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
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #4A90E2;
            margin-bottom: 20px;
        }
        input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4A90E2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #357ABD;
        }
        .volver {
            display: inline-block;
            margin-top: 10px;
            background: #FFD166;
            color: black;
            padding: 8px 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            position: absolute;
            top: 15px;
            left: 15px;
        }
    </style>
</head>
<body>
    <a href="index.php" class="volver">← Volver al inicio</a>

    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="login.php">
            <input type="email" name="correo" placeholder="Correo" required><br>
            <input type="password" name="contrasena" placeholder="Contraseña" required><br>
            <button type="submit">Entrar</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
