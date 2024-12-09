<?php
require_once '../Controller/PartidaController.php';

$controller = new PartidaController();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'moderador') {
    header("Location: pagina_error.php");
    exit;
}

$partidasPendientes = $controller->listarPartidasPendientes($limit, $offset);
$totalPartidasPendientes = Partida::contarPartidasPendientes();
$totalPaginas = ceil($totalPartidasPendientes / $limit);

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
            <?php if (!empty($partidasPendientes)): ?>
                <?php foreach ($partidasPendientes as $partida): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($partida['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($partida['director_nombre']); ?></td>
                        <td>
                            <a href="ficha_partida.php?id=<?php echo $partida['id']; ?>">
                                <img src="../Resources/lupa.png" alt="Ver ficha" style="width: 25px; height: 25px;">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay partidas pendientes para moderar.</td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
        <br>
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
    <br>
    <br>


<?php endif; ?>
<?php include '../Resources/footer.php' ?>
</body>
</html>