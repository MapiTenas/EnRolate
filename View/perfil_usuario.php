<?php
require_once '../Controller/UsuarioController.php';
$controller = new UsuarioController();

// Obtener el id del usuario desde la URL
$idUsuario = isset($_GET['user']) ? intval($_GET['user']) : 0;

// Recuperar el usuario por su id
$usuario = $controller->obtenerUsuario($idUsuario);

if ($usuario) {
    $nombreUsuario = $usuario->getNombreUsuario();
} else {
    $nombreUsuario = "Usuario no encontrado";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate - <?php echo htmlspecialchars($nombreUsuario); ?></title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>
<body>
<?php include '../Resources/header.php' ?>
<br>
<h1>Perfil de <?php echo htmlspecialchars($nombreUsuario); ?>  </h1>
<?php if ($usuario->getTipoUsuario() === 'jugador'): ?>
    <h2>Podrás encontrar a <?php echo htmlspecialchars($nombreUsuario); ?> jugando en:</h2>
<?php elseif ($usuario->getTipoUsuario() === 'director'): ?>
    <h2>Podrás encontrar a <?php echo htmlspecialchars($nombreUsuario); ?> dirigiendo:</h2>
<?php else: ?>
    <h2>Este usuario es un moderador, aqui no hay nada que ver</h2>
<?php endif; ?>
<br>
<br>
<?php include '../Resources/footer.php' ?>
</body>