<?php
include '../Resources/session_start.php';
session_unset();
session_destroy();
header("Location: ../EnRolate/View/index.php");
exit();
