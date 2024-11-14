<?php
require_once '../Resources/session_start.php';
require_once '../Controller/PartidaController.php';
require_once '../Controller/PartidaJugadorController.php';

$controller = new PartidaController();
$controllerJugador = new PartidaJugadorController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$partida = $controller->verPartida($id);
$jugadoresPendientes = $controllerJugador->obtenerJugadoresPendientes($id);

if (!$partida) {
    echo "<h1>No deberias estar viendo esto >:(</h1>";
    exit;
    //Todo: Estaria bien hacer un header() a alguna página de error custom un poco mas elegante??
}

$estadoInscripcion = null;
if (isset($_SESSION['user_id']) && $_SESSION['tipo_usuario'] == 'jugador') {
    $estadoInscripcion = $controllerJugador->obtenerEstadoInscripcion($_SESSION['user_id'], $partida['id']);
}

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
    <img src="<?php echo htmlspecialchars($partida['imagen'] ? $partida['imagen'] : '../Resources/placeholder.jpg'); ?>" alt="Imagen de la actividad" class="activity-image">

    <div class="activity-details">
        <h1><?php echo htmlspecialchars($partida['titulo']); ?></h1>
        <p class="synopsis"><?php echo htmlspecialchars($partida['descripcion']); ?></p>

        <div class="activity-info">
            <div><strong>Sistema de juego:</strong> <?php echo htmlspecialchars($partida['sistema']); ?></div>
            <div><strong>Edad recomendada:</strong> <?php echo htmlspecialchars($partida['edad']); ?></div>
            <div><strong>Horario:</strong> <?php echo htmlspecialchars($partida['franja_horaria']); ?></div>
            <div><strong>Plazas disponibles:</strong> <?php echo htmlspecialchars($partida['plazas_disponibles']); ?></div>
            <div><strong>Director de juego:</strong> <?php echo htmlspecialchars($partida['director_nombre']); ?></div>
        </div>

        <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador' && $partida['estado'] == 'pendiente'): ?>
            <div class="buttons-moderacion">
                <form action="../Controller/PartidaController.php" method="post">
                    <input type="hidden" name="accion" value="aprobar">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($partida['id']); ?>">
                    <button type="submit" class="approve-button">Aprobar partida</button>
                </form>
                <form action="../Controller/PartidaController.php" method="post">
                    <input type="hidden" name="accion" value="rechazar">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($partida['id']); ?>">
                    <button type="submit" class="reject-button">Rechazar partida</button>
                </form>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'jugador'): ?>
            <div class="buttons-moderacion">
                <?php if ($estadoInscripcion === 'pendiente'): ?>
                    <h3>Tu solicitud está pendiente</h3>
                <?php elseif ($estadoInscripcion === 'aceptado'): ?>
                    <h3>Tu solicitud está aprobada</h3>
                <?php elseif ($estadoInscripcion === 'rechazado'): ?>
                    <h3>Tu solicitud ha sido rechazada. No podrás volver a apuntarte a esta partida.</h3>
                <?php elseif (is_null($estadoInscripcion)): ?>
                    <!-- Verificamos si hay plazas disponibles -->
                    <?php if ($partida['plazas_disponibles'] > 0): ?>
                        <form action="../Controller/PartidaJugadorController.php" method="post">
                            <input type="hidden" name="accion" value="apuntarse">
                            <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($partida['id']); ?>">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                            <button type="submit" class="approve-button">Apuntarse a partida</button>
                        </form>
                    <?php else: ?>
                        <h3>La partida ya está llena</h3>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <?php
        if ((isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador') ||
            (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'director' && $_SESSION['user_id'] == $partida['director_id'])):
            ?>
            <div>
                <h2>Inscripciones pendientes</h2>
                <?php if (empty($jugadoresPendientes)): ?>
                    <h4>No hay inscripciones pendientes.</h4>
                <?php else: ?>
                    <table>
                        <thead>
                        <tr>
                            <th>Jugador</th>
                            <th>¿Aceptar o rechazar?</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($jugadoresPendientes as $jugador): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($jugador['nombre_usuario']); ?></td>
                                <td class="btn-mod-jugador-partida">
                                    <form action="../Controller/PartidaJugadorController.php" method="post" style="display:inline;">
                                        <input type="hidden" name="accion" value="aceptar-jugador">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($jugador['id']); ?>">
                                        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($partida['id']); ?>">
                                        <input type="hidden" name="franja_horaria" value="<?php echo htmlspecialchars($partida['franja_horaria']); ?>">
                                        <button type="submit" class="accept-player-btn">
                                            <img src="../Resources/accept.png" alt="Aceptar">
                                        </button>
                                    </form>
                                    <form action="../Controller/PartidaJugadorController.php" method="post" style="display:inline;">
                                        <input type="hidden" name="accion" value="rechazar-jugador">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($jugador['id']); ?>">
                                        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($partida['id']); ?>">
                                        <button type="submit" class="refuse-player-btn">
                                            <img src="../Resources/refuse.png" alt="Rechazar">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>


    </div>
</section>
<div id="error-popup" class="popup"></div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el mensaje de error de la sesión PHP
        var errorMsg = "<?php echo isset($_SESSION['inscripcion_error']) ? $_SESSION['inscripcion_error'] : ''; ?>";
        if (errorMsg) {
            // Mostrar el popup con el mensaje de error
            var popup = document.getElementById("error-popup");
            popup.textContent = errorMsg;
            popup.style.display = "block";

            // Ocultar el popup después de 5 segundos
            setTimeout(function() {
                popup.style.display = "none";
            }, 5000);

            // Limpiar el mensaje de error después de mostrarlo
            <?php $_SESSION['inscripcion_error'] = ''; ?>
        }
    });
</script>

<?php include '../Resources/footer.php' ?>
</body>
</html>