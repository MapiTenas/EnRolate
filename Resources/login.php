<?php
session_start(); // Iniciar sesión PHP

include '../Model/CONNECT-DB.php';

$_SESSION['login_error'] = '';

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = htmlspecialchars($_POST['nombre_usuario']);
    $password = htmlspecialchars($_POST['password']);

    $connection = getDbConnection();

    // Consulta SQL para obtener el usuario por su nombre de usuario
    $stmt = $connection->prepare("SELECT id, nombre_usuario, password, tipo_usuario FROM users WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            // Usuario encontrado, verificar la contraseña
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Contraseña válida, iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

                // Redirigimos a la página principal
                header("Location: ../View/index.php");
                exit();
            } else {
                $_SESSION['login_error'] = 'Contraseña incorrecta.';
            }
        } else {
            $_SESSION['login_error'] = 'Nombre de usuario incorrecto.';
        }
    } else {
        $_SESSION['login_error'] = 'Ocurrió un error al intentar iniciar sesión. Por favor, inténtalo de nuevo más tarde.';
    }
    // Redirigir de vuelta al formulario de login con el mensaje de error
    header("Location: ../View/formulario_login.php");
    exit();

}
?>
