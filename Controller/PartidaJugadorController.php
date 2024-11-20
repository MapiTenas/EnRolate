<?php
require_once '../Resources/session_start.php';
require_once '../Model/PartidaJugador.php';
require_once 'PartidaController.php';

class PartidaJugadorController {
    public function apuntarseAPartida() {
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];

        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, 'pendiente', null);

        // Obtener la franja horaria de la partida a través del modelo
        $franja_horaria = $partidaJugador->obtenerFranjaHorariaPorPartida($game_id);

        // Verificar si el usuario ya tiene una inscripción en la misma franja horaria
        if ($partidaJugador->existeInscripcionEnFranjaHoraria($user_id, $franja_horaria, 'aceptado')) {
            $_SESSION['inscripcion_error'] = "Ya estás inscrito en una partida en la franja horaria seleccionada.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
            exit();
        }
        // Verificar si el usuario ya fue rechazado en la misma partida
        if ($partidaJugador->existeRechazoPrevio($user_id, $game_id)) {
            $_SESSION['inscripcion_error'] = "No puedes volver a inscribirte en una partida de la que ya fuiste rechazado..";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
            exit();
        }
        // Proceder con la inscripción
        $resultado = $partidaJugador->solicitarApuntarse();

        if ($resultado) {
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        } else {
            $_SESSION['inscripcion_error'] = "Error al inscribirse en la partida.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
            exit();
        }
    }

    public function obtenerEstadoInscripcion($user_id, $game_id) {
        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, null, null);
        return $partidaJugador->obtenerEstadoInscripcion($user_id, $game_id);
    }

    public function obtenerJugadoresPendientes($game_id) {
        $partidaJugador = new PartidaJugador(null, null, $game_id, null, null);
        return $partidaJugador->obtenerJugadoresPendientes($game_id);
    }

    public function aceptarJugador() {
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];
        $franja_horaria = (string)$_POST['franja_horaria'];

        // Obtener el número de plazas disponibles en la partida
        $partidaController = new PartidaController();
        $partida = $partidaController->verPartida($game_id);

        // Verificar si hay plazas disponibles
        if ($partida['plazas_disponibles'] <= 0) {
            $_SESSION['inscripcion_error'] = "La partida ya está llena. No se pueden aceptar más jugadores.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
            exit();
        }

        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, null, null);
        // Verificar si el usuario ya tiene una inscripción aceptada en la misma franja horaria
        if ($partidaJugador->existeInscripcionEnFranjaHoraria($user_id, $franja_horaria, 'aceptado')) {
            $_SESSION['inscripcion_error'] = "El jugador ya ha sido aceptado en otra partida en la misma franja horaria.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
            exit();
        }

        // Si hay plazas disponibles, proceder a aceptar al jugador
        $resultado = $partidaJugador->actualizarEstadoJugador($user_id, $game_id, 'aceptado');

        if ($resultado) {
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        } else {
            $_SESSION['inscripcion_error'] = "Error al aceptar al jugador.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        }
        exit();
    }


    public function rechazarJugador() {
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];

        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, null, null);
        $resultado = $partidaJugador->actualizarEstadoJugador($user_id, $game_id, 'rechazado');

        if ($resultado) {
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        } else {
            $_SESSION['inscripcion_error'] = "Error al rechazar al jugador.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        }
        exit();
    }

    public function abandonarPartida(){
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];

        $partidaJugador = new PartidaJugador(null, $user_id,$game_id,null,null);
        $resultado = $partidaJugador->desapuntarseDePartida($user_id,$game_id);

        if ($resultado) {
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        } else {
            $_SESSION['inscripcion_error'] = "Error al desapuntarte de la partida.";
            header("Location: ../View/ficha_partida.php?id=" . $game_id);
        }
        exit();

    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new PartidaJugadorController();

    if (isset($_POST['accion']) && $_POST['accion'] === 'aceptar-jugador') {
        $controller->aceptarJugador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'rechazar-jugador') {
        $controller->rechazarJugador();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'apuntarse') {
        $controller->apuntarseAPartida();
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'abandonar') {
        $controller->abandonarPartida();
    }
}

