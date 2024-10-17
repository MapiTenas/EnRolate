<?php
require_once 'CONNECT-DB.php';

class usuario {
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

    public static function crearUsuario($nombre_usuario, $email, $password, $tipo_usuario, $fecha_registro){

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
