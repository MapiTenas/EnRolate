<?php
require_once '../Controller/UsuarioController.php';
require_once '../Controller/PartidaController.php';
$controller = new UsuarioController();
$partidaController = new PartidaController();

// Obtener el id del usuario desde la URL
$idUsuario = isset($_GET['user']) ? intval($_GET['user']) : 0;

// Recuperar el usuario por su id
$usuario = $controller->obtenerUsuario($idUsuario);

if ($usuario) {
    $nombreUsuario = $usuario->getNombreUsuario();
} else {
    header("Location: pagina_error.php");
    exit;
}
// Recuperar las partidas asociadas al usuario
$partidas = $partidaController->obtenerPartidasPorUsuario($idUsuario);
$partidasDirector = $partidaController->obtenerPartidasPorDirector($idUsuario);
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
<br><br>
<h1>Perfil de <?php echo htmlspecialchars($nombreUsuario); ?>  </h1>
<br>
<?php if ($usuario->getTipoUsuario() === 'jugador'): ?>
    <h2>Podrás encontrar a <?php echo htmlspecialchars($nombreUsuario); ?> jugando en:</h2>
    <br>
    <div class="list-container">
        <table>
            <thead>
            <tr>
                <th>Titulo</th>
                <th>Franja horaria</th>
                <th>Estado solicitud</th>
                <th>Ver partida</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($partidas)): ?>
                <?php foreach ($partidas as $partida): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($partida['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($partida['franja_horaria']); ?></td>
                        <td><?php echo htmlspecialchars($partida['estado_inscripcion']); ?></td>
                        <td>
                            <a href="ficha_partida.php?id=<?php echo htmlspecialchars($partida['id']); ?>">
                                <img src="../Resources/lupa.png" alt="Ver ficha" style="width: 25px; height: 25px;">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No se encontraron partidas para este usuario.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <br>
    <br>

<?php elseif ($usuario->getTipoUsuario() === 'director'): ?>
    <h2>Podrás encontrar a <?php echo htmlspecialchars($nombreUsuario); ?> dirigiendo:</h2>
    <br>
    <div class="list-container">
        <table>
            <thead>
            <tr>
                <th>Titulo</th>
                <th>Franja horaria</th>
                <th>Ver partida</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($partidasDirector)): ?>
                <?php foreach ($partidasDirector as $partida): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($partida['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($partida['franja_horaria']); ?></td>
                        <td>
                            <a href="ficha_partida.php?id=<?php echo htmlspecialchars($partida['id']); ?>">
                                <img src="../Resources/lupa.png" alt="Ver ficha" style="width: 25px; height: 25px;">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No se encontraron partidas para este usuario.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <br>
    <br>
<?php else: ?>
    <?php
    header("Location: pagina_error.php");
    exit;
    ?>
<?php endif; ?>
<br>
<br>
<?php include '../Resources/footer.php' ?>
</body>