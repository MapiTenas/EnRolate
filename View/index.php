<?php include '../Resources/session_start.php';
require_once '../Controller/PartidaController.php';

$controller = new PartidaController();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;
$filtroEdad = isset($_GET['filtro_edad']) ? $_GET['filtro_edad'] : 'todas';
$filtroFranja = isset($_GET['filtro_franja']) ? $_GET['filtro_franja'] : 'todas';
$partidasAprobadas = $controller->listarPartidasAprobadas($limit, $offset, $filtroEdad, $filtroFranja);
$totalPartidasAprobadas = Partida::contarPartidasAprobadas($filtroEdad, $filtroFranja);
$totalPaginas = ceil($totalPartidasAprobadas / $limit);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>
<h1>Bienvenido a enRolate, tu gestor de eventos de partidas de rol</h1>
<?php
if (isset($_SESSION['nombre_usuario'])){
    echo '<h1>Bienvenido, ' . $_SESSION['nombre_usuario'] . '</h1>';
}
?>
<form method="GET" action="" class="dropdown-index">
    <label for="filtro_edad">Filtrar partidas por edad:</label>
    <select name="filtro_edad" id="filtro_edad" class="select-dropdown">
        <option value="todas">Todas</option>
        <option value="todos_los_publicos">Todos los públicos</option>
        <option value="mayores_12">Mayores de 12 años</option>
        <option value="mayores_16">Mayores de 16 años</option>
        <option value="mayores_18">Mayores de 18 años</option>
    </select>

    <label for="filtro_franja">Filtrar partidas por franja horaria:</label>
    <select name="filtro_franja" id="filtro_franja" class="select-dropdown">
        <option value="todas">Todas</option>
        <option value="sabado_mañana">Sábado - Mañana</option>
        <option value="sabado_tarde">Sábado - Tarde</option>
        <option value="domingo_mañana">Domingo - Mañana</option>
        <option value="domingo_tarde">Domingo - Tarde</option>
    </select>

    <button type="submit" class="btn-dropdown">Aplicar filtros</button>
</form>
<div class="grid">
    <div class="list-cards-index">
        <?php foreach ($partidasAprobadas as $partida):?>
        <div class="card-index">
            <img src="<?php echo htmlspecialchars($partida['imagen'] ? $partida['imagen'] : '../Resources/placeholder.jpg'); ?>" alt="Imagen de la partida" class="index-card-image">
            <div class = "header">
                <h2 class="card-header-title clickable">
                    <a href="ficha_partida.php?id=<?php echo $partida['id']; ?>">
                        <?php echo htmlspecialchars($partida['titulo']); ?>
                    </a>
                </h2>
            </div>
            <div class ="card-body">
                <div>
                    <h3>Sistema</h3>
                    <p><?php echo htmlspecialchars($partida['sistema']); ?></p>
                </div>
                    <div>
                        <h3>Jugadores</h3>
                        <p><?php echo htmlspecialchars($partida['numero_jugadores'])?></p> <!--Si quiero que salga el numero actualizado de plazas, con poner 'plazas_restantes' es suficiente -->
                    </div>
                    <div>
                        <h3>Edad recomendada</h3>
                        <p><?php echo htmlspecialchars($partida['edad'])?></p>
                    </div>
            </div>
                <div class="card-footer">
                    <div>
                        <h3>Turno</h3>
                        <p><?php echo htmlspecialchars($partida['franja_horaria'])?></p>
                    </div>
                    <div>
                        <h3>Disponibilidad</h3>
                        <p>
                            <?php
                            if ($partida['plazas_restantes'] > 0) {
                                echo "Inscripción abierta";
                            } else {
                                echo "Partida llena";
                            }
                            ?>
                        </p>
                    </div>
                </div>
        </div>
        <?php endforeach; ?>
</div>
    <!-- Navegación de paginación -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&filtro_edad=<?php echo htmlspecialchars($filtroEdad); ?>&filtro_franja=<?php echo htmlspecialchars($filtroFranja); ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?page=<?php echo $i; ?>&filtro_edad=<?php echo htmlspecialchars($filtroEdad); ?>&filtro_franja=<?php echo htmlspecialchars($filtroFranja); ?>" class="<?php if ($i == $page) echo 'active'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPaginas): ?>
            <a href="?page=<?php echo $page + 1; ?>&filtro_edad=<?php echo htmlspecialchars($filtroEdad); ?>&filtro_franja=<?php echo htmlspecialchars($filtroFranja); ?>">Siguiente</a>
        <?php endif; ?>
    </div>

    <br>
    <br>

    <?php include '../Resources/footer.php' ?>
</body>
</html>