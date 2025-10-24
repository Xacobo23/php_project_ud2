<?php
require "./bd/noteOperations.php";
require "./bd/conection.php";

if (!isset($_SESSION['username'])) {
    header('Location: index.php?msg=Inicia sesión primero');
    exit;
}

// 🌟 Manejo de creación y borrado de notas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear nueva nota
    if (isset($_POST['new_title'], $_POST['new_content'])) {
        $title = trim($_POST['new_title']);
        $content = trim($_POST['new_content']);
        if ($title !== '' && $content !== '') {
            createNote($title, $content); // función que debes tener en noteOperations.php
            header("Location: dashboard.php"); // recarga para ver la nueva nota
            exit;
        }
    }

    // Borrar nota
    if (isset($_POST['delete_id'])) {
        $id = (int)$_POST['delete_id'];
        deleteNote($id); // función que debes tener en noteOperations.php
        header("Location: dashboard.php"); // recarga para actualizar la lista
        exit;
    }
}

$noteList = getAllNotes();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de usuario</title>
    <style>
        main {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1em;
        }
        article {
            background-color: #f9f9f9;
            padding: 1em;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        form { display: inline; }
        button { margin-top: 5px; }
        #newNoteForm {
            margin-bottom: 2em;
            background-color: #fff;
            padding: 1em;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Estás en el área privada.</p>
        <a href="logout.php">Cerrar sesión</a>
    </header>

    <main>
        <!-- 🔹 Formulario para crear nueva nota -->
        <article id="newNoteForm">
            <h2>Crear nueva nota</h2>
            <form method="post">
                <input type="text" name="new_title" placeholder="Título" required>
                <textarea name="new_content" placeholder="Contenido" rows="4" required></textarea>
                <button type="submit">Crear nota</button>
            </form>
        </article>

        <!-- 🔹 Lista de notas existentes -->
        <?php foreach ($noteList as $note): ?>
            <article>
                <h1 class="noteTitle"><?= htmlspecialchars($note->getTitle()) ?></h1>
                <p class="noteText"><?= htmlspecialchars($note->getNoteContent()) ?></p>
                <p class="noteTime"><?= htmlspecialchars($note->getCreatedAt()) ?></p>

                <!-- Botón ver nota -->
                <form action="verNota.php" method="get">
                    <input type="hidden" name="id" value="<?= $note->getId() ?>">
                    <button type="submit">Ver nota completa</button>
                </form>

                <!-- Botón borrar nota -->
                <form method="post" onsubmit="return confirm('¿Seguro que quieres borrar esta nota?');">
                    <input type="hidden" name="delete_id" value="<?= $note->getId() ?>">
                    <button type="submit" style="background-color:red;color:white;">Borrar</button>
                </form>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>
