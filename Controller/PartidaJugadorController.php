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
        // Verificar si el usuario ya fue rechazado en la misma partida
        if ($partidaJugador->existeRechazoPrevio($user_id, $game_id)) {
            echo "<p>Error: No puedes volver a inscribirte en una partida de la que ya fuiste rechazado.</p>";
            return;
        }
        // Proceder con la inscripción
        $resultado = $partidaJugador->solicitarApuntarse();

        if ($resultado) {
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        } else {
            echo "<p>Error al inscribirse en la partida</p>";
        }
    }

    public function obtenerEstadoInscripcion($user_id, $game_id) {
        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, null, null);
        return $partidaJugador->obtenerEstadoInscripcion($user_id, $game_id);
    }


}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'apuntarse') {
    $controller = new PartidaJugadorController();
    $controller->apuntarseAPartida();
}
