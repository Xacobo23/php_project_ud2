<?php
require "./bd/noteOperations.php";
require "./bd/conection.php";

if (!isset($_SESSION['username'])) {
    header('Location: index.php?msg=Inicia sesión primero');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_title'], $_POST['new_content'])) {
        $title = trim($_POST['new_title']);
        $content = trim($_POST['new_content']);
        if ($title !== '' && $content !== '') {
            createNote($title, $content); 
            header("Location: dashboard.php"); 
            exit;
        }
    }

    if (isset($_POST['delete_id'])) {
        $id = (int)$_POST['delete_id'];
        deleteNote($id); 
        header("Location: dashboard.php");
        exit;
    }
}

$noteList = getAllNotes();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notas</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <header>
        <h1>Notas de <?= $_SESSION['username'] ?></h1>
        <a href="logout.php">Cerrar sesión</a>
    </header>

    <main id="dashboardMain">
        <!-- 🔹 Formulario para crear nueva nota -->
        <article id="newNoteForm">
            <h2>Crear nueva nota</h2>
            <form method="post">
                <input type="text" name="new_title" placeholder="Título" required>
                <textarea name="new_content" placeholder="Contenido" rows="4" required></textarea>
                <div id="newButton">
                    <button type="submit">Añadir</button>
                </div>
                
            </form>
        </article>

        <!-- 🔹 Lista de notas existentes -->
        <?php foreach ($noteList as $note): ?>
            <article>
                <div class="top">
                    <h1 class="noteTitle"><?= htmlspecialchars($note->getTitle()) ?></h1>

                    <form method="post" onsubmit="return confirm('¿Seguro que quieres borrar esta nota?');">
                        <input type="hidden" name="delete_id" value="<?= $note->getId() ?>">
                        <button type="submit" class="deleteButton"><img src="./assets/delete.svg" alt="" ></button>
                    </form>
                </div>
                
                <p class="noteText"><?= htmlspecialchars($note->getNoteContent()) ?></p>
                <div class="bottom">
                    <p class="noteTime"><?= htmlspecialchars($note->getCreatedAt()) ?></p>

                    <form action="verNota.php" method="get">
                        <input type="hidden" name="id" value="<?= $note->getId() ?>">
                        <button type="submit" class="moreButton"><img src="./assets/mas.svg" alt=""></button>
                    </form>
                </div>
                

                
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>
