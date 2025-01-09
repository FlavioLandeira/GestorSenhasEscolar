<?php
session_start();


if (isset($_SESSION['user'])) {
    header("Location: view/homepage/homepage.html");
    exit;
}

header("Location: view/homepage/homepage.html");
exit;
?>
