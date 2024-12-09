<?php
require_once '../Controller/UsuarioController.php';

$controller = new UsuarioController();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'moderador') {
    header("Location: pagina_error.php");
    exit;
}

// Obtener usuarios paginados
$usuarios = $controller->listarUsuarios($limit, $offset);

// Contar total de usuarios
$totalUsuarios = Usuario::contarUsuarios();
$totalPaginas = ceil($totalUsuarios / $limit);
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
    <h1>Moderación de usuarios</h1>
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
                    <td>
                        <?php if ($usuario->getTipoUsuario() == 'jugador'): ?>
                            <form method="POST" action="../Controller/UsuarioController.php" class="form-ascenderdegradar">
                                <input type="hidden" name="id" value="<?php echo $usuario->getId(); ?>">
                                <button type="submit" name="ascender" class="btn-ascender">Ascender</button>
                            </form>
                        <?php elseif ($usuario->getTipoUsuario() == 'director'): ?>
                            <form method="POST" action="../Controller/UsuarioController.php" class="form-ascenderdegradar">
                                <input type="hidden" name="id" value="<?php echo $usuario->getId(); ?>">
                                <button type="submit" name="degradar" class="btn-degradar">Degradar</button>
                            </form>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Navegación de paginación -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPaginas): ?>
                <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
            <?php endif; ?>
        </div>

    </div>
<br><br>

<?php endif; ?>
<?php include '../Resources/footer.php' ?>
</body>
</html>