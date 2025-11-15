<?php
session_start();
include("conexion.php"); // debe definir $conn

// 1) Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
$id_usuario = intval($_SESSION['id_usuario']);

// 2) Obtener nombre y trastorno del usuario
$sql_user = "SELECT nombre, transtorno FROM usuarios WHERE id_usuario = $id_usuario LIMIT 1";
$res_user = mysqli_query($conn, $sql_user);
if (!$res_user || mysqli_num_rows($res_user) == 0) {
    header("Location: login.php");
    exit();
}
$user = mysqli_fetch_assoc($res_user);
$nombre = $user['nombre'];
$transtorno_raw = $user['transtorno'] ?? '';

// 3) Normalizar el trastorno para comparar con "categoria" en la tabla actividades
function normalize_key($s) {
    $s = mb_strtolower(trim($s), 'UTF-8');
    if (function_exists('iconv')) {
        $s = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
    } else {
        $search = ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ','ü','Ü','¿','¡'];
        $replace = ['a','e','i','o','u','a','e','i','o','u','n','n','u','u','',''];
        $s = str_replace($search, $replace, $s);
    }
    $s = preg_replace('/[^a-z0-9 ]+/', '', $s);
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s);
}
$key = normalize_key($transtorno_raw);

// 4) Buscar hasta 5 actividades en la tabla actividades donde la categoria coincida
//    Usamos LOWER en SQL para mayor tolerancia; si prefieres exacto, ajusta.
$sql_acts = "SELECT id_actividad, nombre, descripcion, nivel_dificultad, recurso_url
             FROM actividades
             WHERE LOWER(REPLACE(TRIM(categoria), '  ', ' ')) = '". mysqli_real_escape_string($conn, $key) . "'
             ORDER BY id_actividad
             LIMIT 5";

$res_acts = mysqli_query($conn, $sql_acts);

// Si no hay resultados, intentamos una búsqueda menos estricta (LIKE)
if ($res_acts && mysqli_num_rows($res_acts) === 0) {
    // fallback: buscar por LIKE (más flexible)   
    $like = mysqli_real_escape_string($conn, $transtorno_raw);
    $sql_acts = "SELECT id_actividad, nombre, descripcion, nivel_dificultad, recurso_url
                 FROM actividades
                 WHERE LOWER(categoria) LIKE LOWER('%$like%')
                 ORDER BY id_actividad
                 LIMIT 5";
    $res_acts = mysqli_query($conn, $sql_acts);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Actividades - Fund Minds</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f6ff; margin:0; padding:20px; }
    header { background:#4a6cf7; color:#fff; padding:12px; border-radius:8px; text-align:center; }
    .container { max-width:900px; margin:18px auto; background:#fff; padding:18px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
    h2 { margin:0 0 8px 0; color:#333; text-align:center; }
    .actividad { background:#f7fbff; padding:14px; margin:12px 0; border-radius:10px; border-left:4px solid #4a6cf7; }
    .actividad h3 { margin:0 0 6px 0; color:#2b5db3; }
    .actividad p { margin:0 0 8px 0; color:#333; }
    .btn { background:#4a6cf7; color:#fff; border:0; padding:10px 14px; border-radius:8px; cursor:pointer; }
    .btn:hover { background:#3755b0; }
    .completado { background:#27ae60 !important; }
    .volver { display:inline-block; margin-top:16px; padding:10px 14px; background:#6c63ff; color:#fff; text-decoration:none; border-radius:8px; }
    .nota { font-size:0.9rem; color:#666; margin-top:6px; }
</style>
</head>
<body>
<header>
    <strong>Fund Minds</strong> — Actividades para <span style="font-weight:700;"><?php echo htmlspecialchars($nombre); ?></span>
</header>

<div class="container">
    <h2>Actividades para: <span style="color:#4a6cf7; text-transform:capitalize;"><?php echo htmlspecialchars($transtorno_raw ?: 'sin asignar'); ?></span></h2>
    <p class="nota">Aquí verás hasta 5 actividades disponibles para tu trastorno. Pulsa "Marcar como completada" para registrar tu progreso.</p>

    <?php
    if (!$res_acts) {
        echo "<p>Error al consultar actividades: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    } elseif (mysqli_num_rows($res_acts) == 0) {
        echo "<p>No se encontraron actividades para este trastorno. Revisa la categoría en la tabla <code>actividades</code>.</p>";
    } else {
        while ($act = mysqli_fetch_assoc($res_acts)) {
            $id_act = intval($act['id_actividad']);
            $nombre_act = htmlspecialchars($act['nombre']);
            $desc = nl2br(htmlspecialchars($act['descripcion']));
            $nivel = htmlspecialchars($act['nivel_dificultad'] ?? '');
            $recurso = htmlspecialchars($act['recurso_url'] ?? '');
            echo "<div class='actividad'>
                    <h3>{$nombre_act}</h3>
                    <p>{$desc}</p>";
            if ($nivel) echo "<p><b>Dificultad:</b> {$nivel}</p>";
            if ($recurso) echo "<p><a href='{$recurso}' target='_blank'>Ver recurso</a></p>";
            echo "<button class='btn' onclick='guardarProgreso({$id_act}, this)'>Marcar como completada ✅</button>
                  </div>";
        }
    }
    ?>

    <div style="text-align:center; margin-top:18px;">
        <a class="volver" href="principal.php">⬅ Volver a principal</a>
    </div>
</div>

<script>
function guardarProgreso(idActividad, boton) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "guardar_progreso.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            const res = xhr.responseText.trim();
            if (res === "OK" || res.startsWith("OK:")) {
                boton.classList.add('completado');
                boton.innerText = 'Completada ✅';
                boton.disabled = true;
                if (res !== "OK") alert(res);
            } else {
                alert('Respuesta: ' + res);
            }
        } else {
            alert('Error en la petición: ' + xhr.status);
        }
    };
    const params = "id_actividad=" + encodeURIComponent(idActividad);
    xhr.send(params);
}
</script>
</body>
</html>
