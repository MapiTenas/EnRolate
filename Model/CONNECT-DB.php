<?php
function getDbConnection() {
    $dbname = "enrolate";
    $host = "localhost";
    $user = "root";
    $password = "";

    $connection = new mysqli($host, $user, $password, $dbname);

    if ($connection->connect_error) {
        die("No se pudo realizar la conexión: " . $connection->connect_error);
    }

    return $connection;
}
?>
