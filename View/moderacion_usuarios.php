<?php
require_once '../Controller/UsuarioController.php';

$controller = new UsuarioController();
$usuarios = $controller->listarUsuarios();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate - Moderación de usuarios</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>
<br>
<?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador'): ?>
    <h1>Aqui se van a moderar los usuarios</h1>
    <h2>Sobre todo el tema de ascenderlos a Directores de juego </h2>
    <h3>Suerte!</h3>
    <br>

    <div class="list-container">
        <table>
            <thead>
                <tr>
                    <th>Nombre usuario</th>
                    <th>Tipo de usuario</th>
                    <th>Email</th>
                    <th>Acción 1</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario->getNombreUsuario()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getTipoUsuario()); ?></td>
                    <td><?php echo htmlspecialchars($usuario->getEmail()); ?></td>
                    <td><button class="btn-ascender">Ascender</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<br><br>

<?php endif; ?>
<?php include '../Resources/footer.php' ?>
</body>
</html>