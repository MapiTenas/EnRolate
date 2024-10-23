<?php include '../Resources/session_start.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnRolate</title>
    <link rel="shortcut icon" href="favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
<header>
    <div class="logo">
        <img src="/Resources/logo.png" alt="Logo web">
        <a href="../View/index.php" style="text-decoration: none; color: inherit;">
            <h2 class="Nombre web">EnRolate</h2>
        </a>
    </div>

    <nav class="navbar">
        <?php
        if (isset($_SESSION['nombre_usuario'])) {
                if ($_SESSION['tipo_usuario'] == 'moderador'){
                    echo '<a href="../View/moderacion_usuarios.php">Moderación usuarios</a>';
                }  elseif ($_SESSION['tipo_usuario'] == 'director') {
                    echo '<a href="../View/formulario_nueva_partida.php">Envía tu partida</a>';
                }
                // El usuario ha iniciado sesión, mostramos el enlace de Cerrar sesión
                echo '<a href="/Resources/logout.php">Cerrar sesión</a>';
        } else {
            // El usuario no ha iniciado sesión, mostramos los enlaces de Registrate y Crea una nueva cuenta
            echo '<a href="../View/formulario_login.php">Login</a>';
            echo '<a href="../View/formulario_registro.php">Crea una nueva cuenta</a>';
        }
        ?>
</header>
</body>

</html>