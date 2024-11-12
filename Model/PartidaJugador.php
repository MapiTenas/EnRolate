<?php
require_once 'CONNECT-DB.php';

class PartidaJugador {
    private $id;
    private $user_id;
    private $game_id;
    private $estado;
    private $fecha_inscripcion;

    public function __construct($id, $user_id, $game_id, $estado, $fecha_inscripcion){
        $this->id = $id;
        $this->user_id = $user_id;
        $this->game_id = $game_id;
        $this->estado = $estado;
        $this->fecha_inscripcion = $fecha_inscripcion;
    }

    public function solicitarApuntarse() {
        $conexion = getDbConnection();

        $query = "INSERT INTO game_players (user_id, game_id, estado, fecha_inscripcion) VALUES (?, ?, ?, NOW())";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iis", $this->user_id, $this->game_id, $this->estado);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();

        return $resultado;
    }

    public function existeInscripcionEnFranjaHoraria($user_id, $franja_horaria) {
        $conexion = getDbConnection();

        $query = "SELECT COUNT(*) FROM game_players gp 
              JOIN games g ON gp.game_id = g.id 
              WHERE gp.user_id = ? 
              AND g.franja_horaria = ? 
              AND (gp.estado = 'pendiente' OR gp.estado = 'aceptado')";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("is", $user_id, $franja_horaria);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $conexion->close();

        return $count > 0;
    }

    public function obtenerFranjaHorariaPorPartida($game_id) {
        $conexion = getDbConnection();

        $query = "SELECT franja_horaria FROM games WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $stmt->bind_result($franja_horaria);
        $stmt->fetch();
        $stmt->close();
        $conexion->close();

        return $franja_horaria;
    }

    public function existeRechazoPrevio($user_id, $game_id) {
        $conexion = getDbConnection();

        $query = "SELECT COUNT(*) FROM game_players 
              WHERE user_id = ? 
              AND game_id = ? 
              AND estado = 'rechazado'";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $user_id, $game_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $conexion->close();

        return $count > 0;
    }

    public function obtenerEstadoInscripcion($user_id, $game_id) {
        $conexion = getDbConnection();

        $query = "SELECT estado FROM game_players WHERE user_id = ? AND game_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $user_id, $game_id);
        $stmt->execute();
        $stmt->bind_result($estado);
        $stmt->fetch();
        $stmt->close();
        $conexion->close();

        return $estado;
    }

    public function obtenerJugadoresPendientes($game_id) {
        $conexion = getDbConnection();

        $query = "SELECT u.id, u.nombre_usuario FROM game_players gp 
              JOIN users u ON gp.user_id = u.id 
              WHERE gp.game_id = ? AND gp.estado = 'pendiente'";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $jugadores = [];
        while ($fila = $resultado->fetch_assoc()) {
            $jugadores[] = $fila;
        }

        $stmt->close();
        $conexion->close();

        return $jugadores;
    }

    public function actualizarEstadoJugador($user_id, $game_id, $nuevo_estado) {
        $conexion = getDbConnection();

        $query = "UPDATE game_players SET estado = ? WHERE user_id = ? AND game_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sii", $nuevo_estado, $user_id, $game_id);
        $resultado = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $resultado;
    }






    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getGameId()
    {
        return $this->game_id;
    }

    public function setGameId($game_id): void
    {
        $this->game_id = $game_id;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    public function getFechaInscripcion()
    {
        return $this->fecha_inscripcion;
    }

    public function setFechaInscripcion($fecha_inscripcion): void
    {
        $this->fecha_inscripcion = $fecha_inscripcion;
    }




}