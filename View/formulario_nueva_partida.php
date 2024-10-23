<?php
include '../Resources/session_start.php';
require_once '../Controller/PartidaController.php';

$partidaController = new PartidaController();
$partidaController->crearPartida();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate - Envia tu propuesta</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>
<br>
<?php if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'director'): ?>

    <h1>Envia tu partida</h1>
    <h2>Recuerda que antes de ser publicada, pasará por moderación.</h2>
    <div class="form-container">
        <form action="" method="post" class="partidas-form">
            <div class="form-group">
                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="sistema">Sistema de juego:</label>
                <input type="text" id="sistema" name="sistema" required>
            </div>
            <div class="form-group">
                <label for="numero_jugadores">Numero de jugadores:</label>
                <input type="number" id="numero_jugadores" name="numero_jugadores" min="1" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Sinopsis:</label>
                <textarea id="descripcion" name="descripcion" class="sinopsis-textarea" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="edad">Recomendada para:</label>
                <select id="edad" name="edad" required>
                    <option value="" disabled selected>Seleccione una franja de edad</option>
                    <option value="Todos los públicos">Todos los públicos</option>
                    <option value="+12 años">+12 años</option>
                    <option value="+16 años">+16 años</option>
                    <option value="+18 años">+18 años</option>
                </select>
            </div>
            <div class="form-group">
                <label for="franja_horaria">Turno de juego:</label>
                <select id="franja_horaria" name="franja_horaria" required>
                    <option value="" disabled selected>Seleccione una franja horaria</option>
                    <option value="Sabado-Mañana">Sabado-Mañana</option>
                    <option value="Sabado-Tarde">Sabado-Tarde</option>
                    <option value="Domingo-Mañana">Domingo-Mañana</option>
                    <option value="Domingo-Tarde">Domingo-Tarde</option>
                </select>
            </div>
            <input type="hidden" name="director_id" value="<?php echo $_SESSION['user_id']; ?>">
            <div class="form-group">
                <button type="submit" name="submit">Envia tu partida</button>
            </div>
        </form>
    </div>
    <div id="error-popup" class="popup"></div>
    <br>
    <br>

<?php endif; ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el mensaje de error de la sesión PHP
        var errorMsg = "<?php echo isset($_SESSION['partida_error']) ? $_SESSION['partida_error'] : ''; ?>";
        if (errorMsg) {
            // Mostrar el popup con el mensaje de error
            var popup = document.getElementById("error-popup");
            popup.textContent = errorMsg;
            popup.style.display = "block";

            // Ocultar el popup después de 5 segundos
            setTimeout(function() {
                popup.style.display = "none";
            }, 5000);

            // Limpiar el mensaje de error después de mostrarlo
            <?php $_SESSION['partida_error'] = ''; ?>
        }
    });
</script>

<?php include '../Resources/footer.php' ?>

</body>
</html>
