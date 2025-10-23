<?php
session_start();
require './bd/conection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        die("Usuario y contraseña requeridos.");
    }

    $stmt = $conn->prepare("select * from usuarios where username = :username limit 1");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['username'] = $usuario['username'];
            header("Location: dashboard.php"); 
            exit;
        } else {
            header("Location: index.php?msg=Contraseña%20incorrecta");
            exit;
        }
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare("insert into usuarios (username, password) values (:username, :password)");
        $insert->bindParam(':username', $username, PDO::PARAM_STR);
        $insert->bindParam(':password', $passwordHash, PDO::PARAM_STR);

        if ($insert->execute()) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); exit;
        } else {
            echo "Error al crear el usuario.";
        }
    }
}
?>
