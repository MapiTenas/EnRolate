<?php
require_once '../Model/Partida.php';

class PartidaController {
    public function crearPartida() {
        //var_dump($_POST);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            // Recoger datos del formulario
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $franja_horaria = $_POST['franja_horaria'];
            $director_id = $_POST['director_id'];
            $estado = 'pendiente';
            $numero_jugadores = $_POST['numero_jugadores'];
            $sistema = $_POST['sistema'];
            $edad = $_POST['edad'];
            $imagen = '';

            // Crear una nueva partida
            $partida = new Partida(null, $titulo, $descripcion, $franja_horaria, $director_id, $estado, $imagen, null, $numero_jugadores, $sistema, $edad);

            // Verificar si ya existe una partida del mismo director en esa franja horaria
            if ($partida->existePartidaEnFranjaHoraria($director_id, $franja_horaria)) {
                // Guardar el mensaje de error en la sesión
                $_SESSION['partida_error'] = "Ya tienes una partida publicada en la franja horaria seleccionada.";
                header("Location: ../View/formulario_nueva_partida.php");
                exit();
            }

            // Si no hay conflicto, intentamos guardar la partida en la base de datos
            if ($partida->crearPartida()) {
                header("Location: ../View/index.php");
            } else {
                $_SESSION['partida_error'] = "Error al crear la partida.";
                header("Location: ../View/formulario_nueva_partida.php");
                exit();
            }
        }
    }
}

