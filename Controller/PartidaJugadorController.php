<?php
require_once '../Model/PartidaJugador.php';

class PartidaJugadorController {
    public function apuntarseAPartida() {
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];

        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, 'pendiente', null);
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
