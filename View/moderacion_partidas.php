<?php
require_once '../Controller/PartidaController.php';
$controller = new PartidaController();
$partidasPendientes = $controller->listarPartidasPendientes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate - Moderación de partidas</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>
<br>
<?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador'): ?>
    <br>
    <h1>Moderación de partidas</h1>
    <h2>Estas son las partidas con status pendiente</h2>
    <h3>Una vez aprobadas, apareceran en la página principal</h3>


    <br>

    <div class="list-container">
        <table>
            <thead>
            <tr>
                <th>Nombre partida</th>
                <th>Director de juego</th>
                <th>Ver partida</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($partidasPendientes as $partida): ?>
                <tr>
                    <td><?php echo htmlspecialchars($partida['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($partida['director_nombre']); ?></td>
                    <td><a href="ver_partida.php?id=<?php echo $partida['id']; ?>">Ver ficha</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <br>
    <br>


<?php endif; ?>
<?php include '../Resources/footer.php' ?>
</body>
</html>