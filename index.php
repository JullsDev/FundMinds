<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fun Minds - Creciendo con el Corazón</title>
    <style>
        /* ======== ESTILO GENERAL ======== */
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #c2e9fb, #a1c4fd);
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ======== ENCABEZADO ======== */
        header {
            background-color: #fff;
            padding: 15px 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #4b6cb7;
            font-size: 28px;
            margin: 0;
        }

        nav a {
            text-decoration: none;
            color: #4b6cb7;
            font-weight: bold;
            margin-left: 20px;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #182848;
        }

        /* ======== SECCIÓN PRINCIPAL ======== */
        .hero {
            text-align: center;
            padding: 80px 20px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            margin: 40px auto;
            width: 90%;
            max-width: 900px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .hero h2 {
            color: #2d3561;
            font-size: 42px;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 18px;
            color: #333;
            max-width: 700px;
            margin: 0 auto 20px;
            line-height: 1.6;
        }

        .hero button {
            background-color: #6a11cb;
            background-image: linear-gradient(315deg, #6a11cb 0%, #2575fc 74%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .hero button:hover {
            transform: scale(1.05);
        }

        /* ======== PIE DE PÁGINA ======== */
        footer {
            background-color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #555;
            box-shadow: 0 -3px 8px rgba(0,0,0,0.1);
            margin-top: auto;
        }

        footer span {
            color: #2575fc;
            font-weight: bold;
        }

        /* ======== ADAPTACIÓN A MÓVILES ======== */
        @media (max-width: 600px) {
            .hero h2 {
                font-size: 28px; 
            }
            .hero p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- ======== ENCABEZADO ======== -->
    <header>
        <h1>Fun Minds</h1>
        <nav>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarse</a>
        </nav>
    </header>

    <!-- ======== CUERPO PRINCIPAL ======== -->
    <section class="hero">
        <h2>Desarrollando Mentes con Amor</h2>
        <p>
            En <strong>Fun Minds</strong> creemos que cada niño y joven merece la oportunidad de alcanzar su máximo potencial.  
            Nuestra plataforma apoya el aprendizaje y desarrollo cognitivo a través de actividades, progreso personalizado y acompañamiento constante.  
            Trabajamos con empatía, innovación y dedicación para hacer del aprendizaje una experiencia positiva y accesible para todos.
        </p>
        <button onclick="window.location.href='registro.php'">Comienza ahora</button>
    </section>

    <!-- ======== PIE DE PÁGINA ======== -->
    <footer>
        © 2025 <span>Fun Minds</span> | Creciendo juntos con empatía y tecnología 💙
    </footer>

</body>
</html>
