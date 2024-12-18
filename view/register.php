<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../assets/style.css">
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>
    <form method="POST" action="../controllers/AuthController.php">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="action" value="register">Registrar</button>
    </form>
</body>
</html>
