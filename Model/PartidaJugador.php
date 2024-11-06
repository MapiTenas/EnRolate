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

    public function guardar() {
        $conexion = getDbConnection();

        $query = "INSERT INTO game_players (user_id, game_id, estado, fecha_inscripcion) VALUES (?, ?, ?, NOW())";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iis", $this->user_id, $this->game_id, $this->estado);
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