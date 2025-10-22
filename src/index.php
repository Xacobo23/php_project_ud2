<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container">
        <h1>Iniciar sesión</h1>

        <!-- Si login.php redirige aquí con GET ?msg=... puedes mostrar mensajes -->
        <?php if (!empty($_GET['msg'])): ?>
            <div class="msg"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <form action="login.php" method="post" autocomplete="off">
            <label for="username">Usuario</label>
            <input id="username" name="username" type="text" required minlength="3" maxlength="50">

            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" required minlength="6">

            <button type="submit">Entrar</button>
        </form>

        <p class="small">Si el usuario no existe se creará automáticamente con la contraseña indicada.</p>
    </main>
</body>
</html>
