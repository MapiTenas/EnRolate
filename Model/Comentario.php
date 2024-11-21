<?php
require_once 'CONNECT-DB.php';

class Comentario {
    private $id;
    private $game_id;
    private $user_id;
    private $texto;
    private $fecha_comentario;

    public function __construct($id, $game_id, $user_id, $texto, $fecha_comentario)
    {
        $this->id = $id;
        $this->game_id = $game_id;
        $this->user_id = $user_id;
        $this->texto = $texto;
        $this->fecha_comentario = $fecha_comentario;
    }


    public static function crearComentario($game_id, $user_id, $texto) {
        $conexion = getDbConnection();

        $query = "INSERT INTO comments (game_id, user_id, texto) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("iis", $game_id, $user_id, $texto);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}