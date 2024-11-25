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

    public static function obtenerComentariosPorPartida($game_id) {
        $conexion = getDbConnection();

        $query = "
        SELECT 
            c.id,
            c.texto, 
            c.fecha_comentario, 
            u.nombre_usuario,
            c.user_id
        FROM 
            comments c 
        INNER JOIN 
            users u 
        ON 
            c.user_id = u.id 
        WHERE 
            c.game_id = ?
        ORDER BY 
            c.fecha_comentario DESC
    ";

        $stmt = $conexion->prepare($query);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("i", $game_id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $comentarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $comentarios[] = $fila;
        }

        $stmt->close();
        $conexion->close();

        return $comentarios;
    }

    public static function eliminarComentarioPorId($id) {
        $conexion = getDbConnection();
        $query = "DELETE FROM comments WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }
}