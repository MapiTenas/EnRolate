<?php include '../Resources/session_start.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de nuevo usuario</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>
<body>
<?php include '../Resources/header.php' ?>
<h1>Alta de nuevo usuario</h1>
<h2>¡Bienvenid@! </h2>
<h3>Estás apunto de unirte a enRolate, tu gestor de eventos de rol.  </h3>
<h3>Recuerda loggearte para apuntarte a partidas :)</h3>
<br>
<div class="form-container">
    <form action="../Controller/UsuarioController.php" method="post">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" name="submit">Registrar</button>
        </div>
    </form>
</div>
<div id="error-popup" class="popup"></div>
<br>
<?php include '../Resources/footer.php' ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var errorMsg = "<?php echo isset($_SESSION['register_error']) ? $_SESSION['register_error'] : ''; ?>";
        if (errorMsg) {
            var popup = document.getElementById("error-popup");
            popup.textContent = errorMsg;
            popup.style.display = "block";

            setTimeout(function() {
                popup.style.display = "none";
            }, 5000);

            <?php $_SESSION['register_error'] = ''; ?>
        }
    });
</script>