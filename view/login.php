<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="../controllers/AuthController.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="action" value="login">Entrar</button>
    </form>
</body>
</html>
