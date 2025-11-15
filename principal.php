<?php
session_start();
include('conexion.php');

// Verificar que haya sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$query = "SELECT nombre, transtorno FROM usuarios WHERE id_usuario='$id_usuario'";
$resultado = mysqli_query($conn, $query);
$usuario = mysqli_fetch_assoc($resultado);

// Si no tiene trastorno asignado, redirigir al panel
if (empty($usuario['transtorno'])) {
    header("Location: panel.php");
    exit();
}

$transtorno = $usuario['transtorno'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Fund Minds</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #a8e6ff, #f4e1ff);
            color: #333;
            margin: 0;
            text-align: center;
        }
        header {
            background-color: #5aa9e6;
            color: white;
            padding: 20px;
            border-radius: 0 0 20px 20px;
        }
        h1 {
            margin: 0;
        }
        .content {
            padding: 30px;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            padding: 30px;
            width: 85%;
            max-width: 750px;
            margin: 20px auto;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #5aa9e6;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #3d8be4;
        }
        iframe {
            width: 90%;
            max-width: 600px;
            height: 315px;
            border-radius: 15px;
            border: none;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<header>
    <h1>🌟 Bienvenido a Fund Minds, <?php echo $usuario['nombre']; ?> 🌟</h1>
</header>

<div class="content">
    <div class="card">
        <h2>Tu trastorno actual:</h2>
        <p><strong><?php echo $transtorno; ?></strong></p>
        <a href="panel.php?cambiar=1" class="btn">🔄 Cambiar Trastorno</a>
        <a href="logout.php" class="btn" style="background-color:#f76c6c;">🚪 Cerrar Sesión</a>
    </div>

    <div class="card">
        <h2>🧠 Recursos y Actividades Personalizadas</h2>
        <?php
        switch ($transtorno) {
    case "Autismo":
        echo "
        <p>Te recomendamos actividades con patrones, colores y música suave que estimulen tu atención.</p>
        <iframe src='https://www.youtube.com/embed/fBZ-vdreD28?si=-4NmXFIDRmedGIi6' allowfullscreen></iframe>
        <iframe src='https://www.youtube.com/embed/W1_Jg2WDIu4?si=GKqRSk3JQil8DGPB' allowfullscreen></iframe>
        <p><a class='btn' href='actividad.php'>🎨 Actividad </a></p>
        ";
        break;

    case "Dislexia":
        echo "
        <p>Practica con juegos de lectura y sonidos que te ayudarán a mejorar tu comprensión.</p>
        <iframe src='https://www.youtube.com/embed/avGK1xtEqdE?si=NBO4ItMJ6cAbxoxS' allowfullscreen></iframe>
        <iframe src='https://www.youtube.com/embed/JGXcqwSQtqs?si=QIU0HHakULl2EekN' allowfullscreen></iframe>
        <p><a class='btn' href='actividad.php'>📚 Actividad </a></p>
        ";
        break;

    case "TDAH":
        echo "
        <p>Te recomendamos ejercicios de concentración y relajación para mejorar tu enfoque.</p>
        <iframe src='https://www.youtube.com/embed/FOs5CWp3Qr0?si=e-St83_8CVNKTvAD' allowfullscreen></iframe>
        <iframe src='https://www.youtube.com/embed/fsc5O6Y7Etc?si=p37bA3bgpIU7hedI' allowfullscreen></iframe>
        <p><a class='btn' href='actividad.php'>🧘 Actividad </a></p>
        ";
        break;

    case "Sindrome de Down":
        echo "
        <p>Realiza actividades con movimiento y música que fortalecen tu coordinación y memoria.</p>
        <iframe src='https://www.youtube.com/embed/yIBbPQqx3lo?si=Y67FAUZEPURmPvEz' allowfullscreen></iframe>
        <iframe src='https://www.youtube.com/embed/Y5eZ4MLG_Io?si=x9QQMXU9-Oe-bK7w' allowfullscreen></iframe>
        <p><a class='btn' href='actividad.php'>🎵 Actividad </a></p>
        ";
        break;

    case "Sindrome de Aprendizaje":
    echo "
    <p>Trabaja en juegos que estimulen la memoria visual y auditiva.</p>
    <iframe src='https://www.youtube.com/embed/zBpU_EE3MXc?si=Wpe_jODp6ByyeeT8' allowfullscreen></iframe>
    <iframe src='https://www.youtube.com/embed/VNXPPbwwZUE?si=9ASBx-8l9wC9KzAw' allowfullscreen></iframe>
    <p><a class='btn' href='actividad.php'>🎨 Actividad </a></p>
    ";
    break;

    

    case "Estres Post Traumático":
        echo "
        <p>Te ofrecemos técnicas de respiración y relajación para reducir la ansiedad y mejorar tu bienestar.</p>
        <iframe src='https://www.youtube.com/embed/11kv-znBRT0?si=TOXSfpYyEQgRG_Lx' allowfullscreen></iframe>
        <iframe src='https://www.youtube.com/embed/IB8kb7UYbfY?si=XRXhTh7Yd1AXxWSq' allowfullscreen></iframe>
        <p><a class='btn' href='actividad.php'>🌿 Actividad </a></p>
        ";
        break;

    default:
        echo "<p>No se ha asignado un trastorno.</p>";
        break;
}

        ?>
    </div>
</div>

</body>
</html>
