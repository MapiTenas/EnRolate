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
    <form action="" method="post">
        <div class="form-group">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" value="" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" value="" required>
        </div>
        <div class="form-group">
            <button type="submit" name="submit">Registrar</button>
        </div>
    </form>
</div>
<div id="error-popup" class="popup"></div>
<br>
<?php include '../Resources/footer.php' ?>
