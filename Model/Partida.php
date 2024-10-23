<?php
require_once 'CONNECT-DB.php';

class Partida {
    private $id;
    private $titulo;
    private $descripcion;
    private $franja_horaria;
    private $director_id;
    private $estado;
    private $imagen;
    private $fecha_creacion;
    private $numero_jugadores;
    private $sistema;
    private $edad;

    public function __construct($id, $titulo, $descripcion, $franja_horaria, $director_id, $estado, $imagen, $fecha_creacion, $numero_jugadores, $sistema, $edad)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->franja_horaria = $franja_horaria;
        $this->director_id = $director_id;
        $this->estado = $estado;
        $this->imagen = $imagen;
        $this->fecha_creacion = $fecha_creacion;
        $this->numero_jugadores = $numero_jugadores;
        $this->sistema = $sistema;
        $this->edad = $edad;
    }

    public function crearPartida() {
        $conexion = getDbConnection();
        $query = "INSERT INTO games (titulo, descripcion, franja_horaria, director_id, numero_jugadores, sistema, edad) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssssss", $this->titulo, $this->descripcion, $this->franja_horaria, $this->director_id, $this->numero_jugadores, $this->sistema, $this->edad);

        // Manejo de errores
        if ($stmt->execute()) {
            $conexion->close();
            return true;
        } else {
            echo "Error: " . $stmt->error;
            $conexion->close();
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getFranjaHoraria()
    {
        return $this->franja_horaria;
    }

    public function setFranjaHoraria($franja_horaria): void
    {
        $this->franja_horaria = $franja_horaria;
    }

    public function getDirectorId()
    {
        return $this->director_id;
    }

    public function setDirectorId($director_id): void
    {
        $this->director_id = $director_id;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado): void
    {
        $this->estado = $estado;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion($fecha_creacion): void
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getNumeroJugadores()
    {
        return $this->numero_jugadores;
    }

    public function setNumeroJugadores($numero_jugadores): void
    {
        $this->numero_jugadores = $numero_jugadores;
    }

    public function getSistema()
    {
        return $this->sistema;
    }
    public function setSistema($sistema): void
    {
        $this->sistema = $sistema;
    }

    public function getEdad()
    {
        return $this->edad;
    }

    public function setEdad($edad): void
    {
        $this->edad = $edad;
    }

}