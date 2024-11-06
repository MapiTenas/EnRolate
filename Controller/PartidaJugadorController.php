<?php
require_once '../Model/PartidaJugador.php';

class PartidaJugadorController {
    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'apuntarse') {
            $this->apuntarseAPartida();
        }
    }

    private function apuntarseAPartida() {
        // Recupera los datos del formulario
        $user_id = (int)$_POST['user_id'];
        $game_id = (int)$_POST['game_id'];

        // Crea el objeto del modelo y llama a su método de inserción
        $partidaJugador = new PartidaJugador(null, $user_id, $game_id, 'pendiente', null);
        $resultado = $partidaJugador->guardar();

        // Redirige o muestra mensaje de éxito/error
        if ($resultado) {
            header("Location: ../View/index.php"); // Cambia por una página de éxito
        } else {
            echo "<p>Error al inscribirse en la partida</p>";
        }
    }
}

new PartidaJugadorController();
?>
