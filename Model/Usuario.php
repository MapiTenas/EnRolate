<?php
require_once 'CONNECT-DB.php';

class Usuario {
    private $id;
    private $nombre_usuario;
    private $email;
    private $password;
    private $tipo_usuario;
    private $fecha_registro;

    public function __construct($id, $nombre_usuario, $email, $password, $tipo_usuario, $fecha_registro)
    {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->email = $email;
        $this->password = $password;
        $this->tipo_usuario = $tipo_usuario;
        $this->fecha_registro = $fecha_registro;
    }

    public function crearUsuario(){
        $conexion = getDbConnection();
        $query = "INSERT INTO users (nombre_usuario, email, password) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sss", $this->nombre_usuario, $this->email, $this->password);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;

    }

    public static function obtenerTodosLosUsuarios($limit, $offset) {
        $conexion = getDbConnection();
        $query = "SELECT id, nombre_usuario, email, tipo_usuario, fecha_registro FROM users LIMIT ? OFFSET ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $usuarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = new Usuario(
                $fila['id'],
                $fila['nombre_usuario'],
                $fila['email'],
                '',
                $fila['tipo_usuario'],
                $fila['fecha_registro']
            );
        }

        $stmt->close();
        $conexion->close();
        return $usuarios;
    }

    public function ascenderUsuario() {
        $conexion = getDbConnection();
        $query = "UPDATE users SET tipo_usuario = 'director' WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $this->id);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function degradarUsuario() {
        $conexion = getDbConnection();
        $query = "UPDATE users SET tipo_usuario = 'jugador' WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $this->id);
        $resultado = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public static function contarUsuarios() {
        $conexion = getDbConnection();
        $query = "SELECT COUNT(*) AS total FROM users";
        $resultado = $conexion->query($query);
        $total = $resultado->fetch_assoc()['total'];
        $conexion->close();
        return $total;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getNombreUsuario()
    {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario($nombre_usuario): void
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }


    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getTipoUsuario()
    {
        return $this->tipo_usuario;
    }


    public function setTipoUsuario($tipo_usuario): void
    {
        $this->tipo_usuario = $tipo_usuario;
    }


    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }


    public function setFechaRegistro($fecha_registro): void
    {
        $this->fecha_registro = $fecha_registro;
    }

}
