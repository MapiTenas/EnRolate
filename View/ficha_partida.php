<?php
require_once '../Controller/PartidaController.php';

$controller = new PartidaController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$partida = $controller->verPartida($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate - <?php echo htmlspecialchars($partida['titulo']); ?></title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>

<section class="activity-card">
    <img src="<?php echo htmlspecialchars($partida['imagen']); ?>" alt="Imagen de la actividad" class="activity-image">

    <div class="activity-details">
        <h1><?php echo htmlspecialchars($partida['titulo']); ?></h1>
        <p class="synopsis"><?php echo htmlspecialchars($partida['descripcion']); ?></p>

        <div class="activity-info">
            <div><strong>Sistema de juego:</strong> <?php echo htmlspecialchars($partida['sistema']); ?></div>
            <div><strong>Edad recomendada:</strong> <?php echo htmlspecialchars($partida['edad']); ?></div>
            <div><strong>Horario:</strong> <?php echo htmlspecialchars($partida['franja_horaria']); ?></div>
            <div><strong>Plazas disponibles:</strong> <?php echo htmlspecialchars($partida['numero_jugadores']); ?></div>
            <div><strong>Director de juego:</strong> <?php echo htmlspecialchars($partida['director_nombre']); ?></div>
        </div>

        <?php if ($_SESSION['tipo_usuario'] == 'moderador'): ?>
            <div class="buttons-moderacion">
                <button class="approve-button">Aprobar partida</button>
                <button class="reject-button">Rechazar partida</button>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../Resources/footer.php' ?>
</body>
</html>