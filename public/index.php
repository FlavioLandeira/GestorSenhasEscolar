<?php
session_start();
require_once '../controllers/AuthController.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $authController->register($_POST['name'], $_POST['email'], $_POST['password'], $_POST['type'], $_POST['localId']);
    }

    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        if ($authController->login($_POST['email'], $_POST['password'])) {
            header('Location: dashboard.php');
        } else {
            echo 'Login failed!';
        }
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SenhaSmart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Bem-vindo ao SenhaSmart</h1>
    <form method="POST">
        <input type="hidden" name="action" value="login">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <form method="POST">
        <input type="hidden" name="action" value="register">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="type" placeholder="Type" required>
        <input type="number" name="localId" placeholder="Local ID" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
