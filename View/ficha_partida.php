<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>

<section class="activity-card">
    <img src="../uploads/d&d2.jpg" alt="Imagen de la actividad" class="activity-image">

    <div class="activity-details">
        <h1>Una noche en Night City</h1>
        <p class="synopsis">
            Noviembre del 2077, una noche cualquiera en Night City. Algo está pasando en el Afterlife, el local de fixers y mercs por excelencia... Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>

        <div class="activity-info">
            <div><strong>Sistema de juego:</strong> Cyberpunk:Red</div>
            <div><strong>Edad recomendada:</strong> +18 años</div>
            <div><strong>Horario:</strong> Sábado - mañana</div>
            <div><strong>Plazas disponibles:</strong> 4</div>
        </div>
        <?php
        if ($_SESSION['tipo_usuario'] == 'moderador') {
            echo '<div class="buttons-moderacion">
            <button class="approve-button">Aprobar partida</button>
            <button class="reject-button">Rechazar partida</button>
        </div>';
        }
        ?>

    </div>
</section>

<?php include '../Resources/footer.php' ?>
</body>
</html>