<?php
session_start();
require './bd/conection.php'; // Asegúrate de que este archivo exista y funcione

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        die("Usuario y contraseña requeridos.");
    }

    // 1. Buscar usuario
    $stmt = $conn->prepare("select * from usuarios where username = :username limit 1");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        // 2. Usuario existe, verificar contraseña
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $usuario['password'])) {
            // Contraseña válida → iniciar sesión
            $_SESSION['username'] = $usuario['username'];
            //echo "Bienvenido, " . htmlspecialchars($usuario['username']) . "!";
            header("Location: dashboard.php"); 
            exit;
        } else {
            header("Location: index.php?msg=Contraseña%20incorrecta");
            exit;
        }
    } else {
        // 3. Usuario no existe → crear uno nuevo
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare("insert into usuarios (username, password) values (:username, :password)");
        $insert->bindParam(':username', $username, PDO::PARAM_STR);
        $insert->bindParam(':password', $passwordHash, PDO::PARAM_STR);

        if ($insert->execute()) {
            // Registro exitoso → iniciar sesión
            $_SESSION['username'] = $username;
            echo "Usuario nuevo creado. Bienvenido, " . htmlspecialchars($username) . "!";
            // header("Location: dashboard.php"); exit;
        } else {
            echo "Error al crear el usuario.";
        }
    }
}
?>
