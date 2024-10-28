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

            // Manejar la subida de la imagen
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $target_dir = "../uploads/";  // Directorio donde guardarás las imágenes
                $target_file = $target_dir . basename($_FILES["imagen"]["name"]);

                // Verificar si el archivo es una imagen
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["imagen"]["tmp_name"]);
                if ($check !== false) {
                    // Mover el archivo subido a la carpeta de destino
                    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                        $imagen = $target_file;  // Guardar la ruta de la imagen
                    } else {
                        $_SESSION['partida_error'] = "Error al subir la imagen.";
                        header("Location: ../View/formulario_nueva_partida.php");
                        exit();
                    }
                } else {
                    $_SESSION['partida_error'] = "El archivo no es una imagen.";
                    header("Location: ../View/formulario_nueva_partida.php");
                    exit();
                }
            }

            // Crear una nueva partida con la imagen
            $partida = new Partida(null, $titulo, $descripcion, $franja_horaria, $director_id, $estado, $imagen, null, $numero_jugadores, $sistema, $edad);

            // Verificar si ya existe una partida del mismo director en esa franja horaria
            if ($partida->existePartidaEnFranjaHoraria($director_id, $franja_horaria)) {
                $_SESSION['partida_error'] = "Ya tienes una partida publicada en la franja horaria seleccionada.";
                header("Location: ../View/formulario_nueva_partida.php");
                exit();
            }

            // Guardar la partida en la base de datos
            if ($partida->crearPartida()) {
                header("Location: ../View/index.php");
            } else {
                $_SESSION['partida_error'] = "Error al crear la partida.";
                header("Location: ../View/formulario_nueva_partida.php");
                exit();
            }
        }
    }
    public function listarPartidasPendientes() {
        $partidasPendientes = Partida::obtenerPartidasPendientes();
        return $partidasPendientes;
    }


}

