<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <img src="../assets/imgs/logo.png" alt="Logo" class="logo"> <!-- Logo aqui -->
        <h1>Login</h1>
        <form method="POST" action="../controllers/AuthController.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit" name="action" value="login">Entrar</button>
            <a href="register.php">Registrar</a>
        </form>
    </div>
</body>
</html>
