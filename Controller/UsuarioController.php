<?php
require_once '../Model/Usuario.php';

class UsuarioController {

    public function crearUsuario($nombre_usuario, $email, $password) {
        $conexion = getDbConnection();
        // Comprobar si el nombre de usuario o el email ya existen
        $query = "SELECT id FROM users WHERE nombre_usuario = ? OR email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $nombre_usuario, $email);
        $stmt->execute();
        $stmt->store_result();

        // Si ya existe un resultado, devolvemos un error
        if ($stmt->num_rows > 0) {
            $stmt->close();
            $conexion->close();
            if (isset($_SESSION)) {
                $_SESSION['register_error'] = "El nombre de usuario o el correo ya están registrados.";
            }
            return false;
        }

        $stmt->close();
        $conexion->close();

        // Si no hay duplicados, continuamos con la inserción
        $password_hashed = password_hash(htmlspecialchars($password), PASSWORD_BCRYPT);
        $usuario = new Usuario(null, $nombre_usuario, $email, $password_hashed, null, null);
        return $usuario->crearUsuario();
    }

    // Método para listar usuarios
    public function listarUsuarios($limit, $offset) {
        return Usuario::obtenerTodosLosUsuarios($limit, $offset);
    }

    public function ascender($id) {
        $usuario = new Usuario($id, null, null, null, null, null);
        return $usuario->ascenderUsuario();
    }

    public function degradar($id) {
        $usuario = new Usuario($id, null, null, null, null, null);
        return $usuario->degradarUsuario();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    session_start();
    $nombre_usuario = $_POST['nombre_usuario'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $controller = new UsuarioController();
    if ($controller->crearUsuario($nombre_usuario, $email, $password)) {
        header("Location: ../View/index.php");
    } else {
        header("Location: ../View/formulario_registro.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ascender'])) {
    $controller = new UsuarioController();
    if ($controller->ascender($_POST['id'])) {
        header("Location: ../View/moderacion_usuarios.php");
    } else {
        echo "Error al ascender al usuario.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['degradar'])) {
    $controller = new UsuarioController();
    if ($controller->degradar($_POST['id'])) {
        header("Location: ../View/moderacion_usuarios.php");
    } else {
        echo "Error al degradar al usuario.";
    }
}


