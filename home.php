<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html'); // Redireciona para o login se a sessão não estiver ativa
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Você está na página inicial.</p>
</body>
</html>