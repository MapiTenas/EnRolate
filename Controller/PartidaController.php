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
    public function listarPartidasPendientes($limit, $offset) {
        $partidasPendientes = Partida::obtenerPartidasPendientes($limit, $offset);
        return $partidasPendientes;
    }

    public function verPartida($id) {
        session_start();
        $partida = Partida::obtenerPartidaPorId($id);
        //Ahora también funciona si no hay ningun tipo de sesión iniciada
        if ($partida['estado'] === 'pendiente' && (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'moderador')) {
            return null;
        }

        return $partida; // Devolvemos la partida si es moderador o está aprobada
    }

    public function aprobarPartida($id) {
        session_start();

        // Verificamos si el usuario es un moderador
        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador') {
            if (Partida::aprobarPartida($id)) {
                $_SESSION['mensaje'] = "Partida aprobada con éxito.";
            } else {
                $_SESSION['error'] = "Error al aprobar la partida.";
            }
        } else {
            $_SESSION['error'] = "No tienes permisos para aprobar esta partida.";
        }

        // Redireccionamos de vuelta a la página de visualización de la partida
        header("Location: ../View/ficha_partida.php?id=" . $id);
        exit();
    }

    public function rechazarPartida($id) {
        session_start();

        // Verificamos si el usuario es un moderador
        if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador') {
            if (Partida::rechazarPartida($id)) {
                $_SESSION['mensaje'] = "Partida rechazada con éxito.";
            } else {
                $_SESSION['error'] = "Error al rechazada la partida.";
            }
        } else {
            $_SESSION['error'] = "No tienes permisos para aprobar esta partida.";
        }

        // Redireccionamos de vuelta a la página de moderación de partidas
        header("Location: ../View/moderacion_partidas.php");
        exit();
    }

    public function listarPartidasAprobadas($limit, $offset, $filtro = null) {
        return Partida::obtenerPartidasAprobadas($limit, $offset, $filtro);
    }



}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $id = (int)$_POST['id'];

    $controller = new PartidaController();

    switch ($accion) {
        case 'aprobar':
            $controller->aprobarPartida($id);
            break;
        case 'rechazar':
            $controller->rechazarPartida($id);
            break;
    }
}

