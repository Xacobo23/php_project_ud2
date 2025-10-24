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
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="loginBody">
    <main class="loginMain">
        <h1>Iniciar sesión</h1>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="msg"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <form action="login.php" method="post" autocomplete="off">
            <div>
                <label for="username">Usuario</label>
                <input id="username" name="username" type="text" required minlength="3" maxlength="20">
            </div>
            
            <div>
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" required minlength="6">
            </div>
            

            <button type="submit">Entrar</button>
        </form>

        <!-- <p class="small">Si el usuario no existe se creará automáticamente con la contraseña indicada.</p> -->
    </main>
</body>
</html>
