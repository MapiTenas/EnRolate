<?php
require_once '../Model/Usuario.php';

class UsuarioController {

    public function crearUsuario($nombre_usuario, $email, $password) {
        $password_hashed = password_hash(htmlspecialchars($password), PASSWORD_BCRYPT);

        $usuario = new Usuario(null, $nombre_usuario, $email, $password_hashed, null, null);
        return $usuario->crearUsuario();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $controller = new UsuarioController();
    if ($controller->crearUsuario($nombre_usuario, $email, $password)) {
        echo "Usuario registrado con éxito.";
    } else {
        echo "Error al registrar el usuario.";
    }
}


