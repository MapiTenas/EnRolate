<?php
include '../Resources/session_start.php';
session_unset();
session_destroy();
header("Location: ../View/index.php");
exit();
