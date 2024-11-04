<?php include '../Resources/session_start.php';
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
    if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'moderador') {
        echo '<h2>Como eres moderador, puedes ver esto.</h2>';
    }
}
?>
<div class="grid">
    <div class="list-cards-index">
        <div class="card-index">
            <img src="../uploads/d&d2.jpg" class="index-card-image">
            <div class = "header">
                <h2 class="card-header-title clickable">Titulo de la partida</h2>
            </div>
            <div class ="card-body">
                <div>
                    <h3>Sistema</h3>
                    <p>D&D</p>
                </div>
                    <div>
                        <h3>Jugadores</h3>
                        <p>4</p>
                    </div>
                    <div>
                        <h3>Edad recomendada</h3>
                        <p>+12 años</p>
                    </div>
            </div>
                <div class="card-footer">
                    <div>
                        <h3>Turno</h3>
                        <p>Mañana-sábado</p>
                    </div>
                    <div>
                        <h3>Disponibilidad</h3>
                        <p>Inscripción abierta</p>
                    </div>
                </div>
        </div>
</div>
    <br>
    <br>

    <?php include '../Resources/footer.php' ?>
</body>
</html>