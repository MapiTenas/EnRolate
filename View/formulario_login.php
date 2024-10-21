<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EnRolate - Login</title>
    <link rel="stylesheet" href="../Resources/styles.css">
</head>

<body>
<?php include '../Resources/header.php' ?>
<br>
<h1>Iniciar sesión</h1>
<h2>Estás a un clic de poder empezar a apuntarte a partidas </h2>
<h3>Introduce tus datos</h3>
<br>
<div class="form-container">
    <form action="../Resources/login.php" method="post" class="generic-form">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" name="submit">Iniciar sesión</button>
        </div>
    </form>
</div>

<div id="error-popup" class="popup"></div>

<h3>Recuerda mantener tu contraseña secreta</h3>

<br>
<br>

<?php include '../Resources/footer.php' ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener el mensaje de error de la sesión PHP
        var errorMsg = "<?php echo isset($_SESSION['login_error']) ? $_SESSION['login_error'] : ''; ?>";
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
            <?php $_SESSION['login_error'] = ''; ?>
        }
    });
</script>



</body>
</html>