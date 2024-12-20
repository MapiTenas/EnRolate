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

        if ($this->existePartidaEnFranjaHoraria($this->director_id, $this->franja_horaria)) {
            echo "Error: Ya tienes una partida registrada en esta franja horaria.";
            return false;
        }

        $query = "INSERT INTO games (titulo, descripcion, franja_horaria, director_id, numero_jugadores, sistema, edad, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ssssssss", $this->titulo, $this->descripcion, $this->franja_horaria, $this->director_id, $this->numero_jugadores, $this->sistema, $this->edad, $this->imagen);

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

    public function existePartidaEnFranjaHoraria($director_id, $franja_horaria) {
        $conexion = getDbConnection();
        $query = "SELECT COUNT(*) FROM games WHERE director_id = ? AND franja_horaria = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("is", $director_id, $franja_horaria);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $conexion->close();

        return $count > 0;
    }

    public static function obtenerPartidasPendientes($limit, $offset) {
        $conexion = getDbConnection();
        $query = "SELECT games.id, games.titulo, users.nombre_usuario AS director_nombre 
              FROM games 
              JOIN users ON games.director_id = users.id 
              WHERE games.estado = 'pendiente'
               LIMIT ? OFFSET ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $limit,$offset);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $partidasPendientes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $partidasPendientes[] = $fila;
        }

        $stmt->close();
        $conexion->close();
        return $partidasPendientes;
    }
    public static function contarPartidasPendientes() {
        $conexion = getDbConnection();
        $query = "SELECT COUNT(*) AS total FROM games WHERE estado = 'pendiente'";
        $resultado = $conexion->query($query);
        $total = $resultado->fetch_assoc()['total'];
        $conexion->close();
        return $total;
    }

    public static function obtenerPartidaPorId($id) {
        $conexion = getDbConnection();
        $query = "SELECT games.*, users.nombre_usuario AS director_nombre, 
              (games.numero_jugadores - 
                 (SELECT COUNT(*) FROM game_players 
                  WHERE game_id = games.id AND estado = 'aceptado')
              ) AS plazas_disponibles
              FROM games 
              JOIN users ON games.director_id = users.id 
              WHERE games.id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $partida = $resultado->fetch_assoc();

        $stmt->close();
        $conexion->close();
        return $partida;
    }

    public static function aprobarPartida($id) {
        $conexion = getDbConnection();
        $query = "UPDATE games SET estado = 'aprobada' WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);

        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();

        return $resultado;
    }

    public static function rechazarPartida($id) {
        $conexion = getDbConnection();
        $query = "UPDATE games SET estado = 'cerrada' WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);

        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();

        return $resultado;
    }

    public static function obtenerPartidasAprobadas($limit, $offset, $filtroEdad = null, $filtroFranja = null) {
        $conexion = getDbConnection();
        $query = "
            SELECT g.*, 
               (g.numero_jugadores - 
               COALESCE((SELECT COUNT(*) 
                         FROM game_players gp 
                         WHERE gp.game_id = g.id AND gp.estado = 'aceptado'), 0)) AS plazas_restantes
            FROM games g
            WHERE g.estado = 'aprobada'";

        // Filtros
        if ($filtroEdad === 'mayores_12') {
            $query .= " AND edad = '+12 años'";
        } elseif ($filtroEdad === 'mayores_16') {
            $query .= " AND edad = '+16 años'";
        } elseif ($filtroEdad === 'mayores_18') {
            $query .= "AND edad = '+18 años'";
        } elseif ($filtroEdad === 'todos_los_publicos') {
            $query .= "AND edad = 'Todos los públicos'";
        }

        if ($filtroFranja === 'sabado_mañana') {
            $query .= "AND franja_horaria = 'Sabado-Mañana'";
        } elseif ($filtroFranja === 'sabado_tarde'){
            $query .= "AND franja_horaria = 'Sabado-Tarde'";
        } elseif ($filtroFranja === 'domingo_mañana'){
            $query .= "AND franja_horaria = 'Domingo-Mañana'";
        } elseif ($filtroFranja === 'domingo_tarde') {
            $query .= "AND franja_horaria = 'Domingo-Tarde'";
        }

            $query .= " LIMIT ? OFFSET ?";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $partidas = [];
        while ($row = $result->fetch_assoc()) {
            $partidas[] = $row;
        }

        $conexion->close();
        return $partidas;
    }

    public static function contarPartidasAprobadas($filtroEdad = null, $filtroFranja = null) {
        $conexion = getDbConnection();
        $query = "SELECT COUNT(*) AS total FROM games WHERE estado = 'aprobada'";

        // Filtros
        if ($filtroEdad === 'mayores_12') {
            $query .= " AND edad = '+12 años'";
        } elseif ($filtroEdad === 'mayores_16') {
            $query .= " AND edad = '+16 años'";
        } elseif ($filtroEdad === 'mayores_18') {
            $query .= "AND edad = '+18 años'";
        } elseif ($filtroEdad === 'todos_los_publicos') {
            $query .= "AND edad = 'Todos los públicos'";
        }

        if ($filtroFranja === 'sabado_mañana') {
            $query .= "AND franja_horaria = 'Sabado-Mañana'";
        } elseif ($filtroFranja === 'sabado_tarde'){
            $query .= "AND franja_horaria = 'Sabado-Tarde'";
        } elseif ($filtroFranja === 'domingo_mañana'){
            $query .= "AND franja_horaria = 'Domingo-Mañana'";
        } elseif ($filtroFranja === 'domingo_tarde') {
            $query .= "AND franja_horaria = 'Domingo-Tarde'";
        }

        $stmt = $conexion->prepare($query);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $total = $resultado->fetch_assoc()['total'];

        $conexion->close();
        return $total;
    }
    public static function eliminarPartidaPorId($id) {
        $conexion = getDbConnection();
        $query = "DELETE FROM games WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    public static function obtenerPartidasPorUsuario($userId) {
        $conexion = getDbConnection();

        $query = "SELECT g.*, gp.estado AS estado_inscripcion
        FROM games g
        JOIN game_players gp 
        ON g.id = gp.game_id
        WHERE gp.user_id = ?";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $partidas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $partidas[] = $fila;
        }

        $stmt->close();
        $conexion->close();

        return $partidas;
    }

    public static function obtenerPartidasDeDirector($userId){
        $conexion = getDbConnection();

        $query = "SELECT * FROM games WHERE director_id = ? AND estado = 'aprobada';";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $partidasDirector = [];
        while ($fila = $resultado->fetch_assoc()) {
            $partidasDirector[] = $fila;
        }

        $stmt->close();
        $conexion->close();

        return $partidasDirector;
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