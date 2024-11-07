<?php
require_once '../Model/PartidaJugador.php';

class PartidaJugadorController {
    public function apuntarseAPartida() {
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];

        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, 'pendiente', null);

        // Obtener la franja horaria de la partida a través del modelo
        $franja_horaria = $partidaJugador->obtenerFranjaHorariaPorPartida($game_id);

        // Verificar si el usuario ya tiene una inscripción en la misma franja horaria
        if ($partidaJugador->existeInscripcionEnFranjaHoraria($user_id, $franja_horaria)) {
            echo "<p>Error: Ya estás inscrito en una partida en la franja horaria seleccionada.</p>";
            return;
        }

        // Proceder con la inscripción
        $resultado = $partidaJugador->solicitarApuntarse();

        if ($resultado) {
            header("Location: ../View/index.php");
        } else {
            echo "<p>Error al inscribirse en la partida</p>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'apuntarse') {
    $controller = new PartidaJugadorController();
    $controller->apuntarseAPartida();
}
