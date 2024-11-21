<?php
require_once '../Resources/session_start.php';
require_once '../Model/Comentario.php';

class ComentarioController {
    public function guardarComentario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game_id = $_POST['game_id'] ?? null;
            $user_id = $_POST['user_id'] ?? null;
            $texto = $_POST['comentario'] ?? '';

            if (!$game_id || !$user_id || empty($texto)) {
                // Manejar error si faltan datos
                $_SESSION['inscripcion_error'] = "Todos los campos son obligatorios.";
                header("Location: ../View/ficha_partida.php?id=" . $game_id);
                exit;
            }

            $resultado = Comentario::crearComentario($game_id, $user_id, $texto);

            if ($resultado) {
                // Redirigir de vuelta a la ficha de la partida
                header("Location: ../View/ficha_partida.php?id=" . $game_id);
            } else {
                // Manejar error de inserción
                $_SESSION['inscripcion_error'] = "Error al guardar el comentario.";
                header("Location: ../View/ficha_partida.php?id=" . $game_id);
            }
        }
    }
    public function obtenerComentariosPorPartida($game_id) {
        return Comentario::obtenerComentariosPorPartida($game_id);
    }

}

// Ejecutar el método
$controller = new ComentarioController();
$controller->guardarComentario();
