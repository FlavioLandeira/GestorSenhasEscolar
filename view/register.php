<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="login.css">
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="login-container">
    <img src="../assets/imgs/logo.png" alt="Logo" class="logo"> <!-- Logo aqui -->
    <h1>Register</h1>
    <form method="POST" action="../controllers/AuthController.php">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="action" value="register">Registrar</button>
        <a href="login.php">Login</a>
    </form>
    </div>
</body>
</html>
