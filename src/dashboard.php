<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header('Location: index.php?msg=Inicia sesión primero');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de usuario</title>
</head>
<body>
    <header>
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Estás en el área privada.</p>
        <a href="logout.php">Cerrar sesión</a>
    </header>
    
</body>
</html>
