<?php
require "./bd/noteOperations.php";
require "./bd/conection.php";


if (!isset($_SESSION['username'])) {
    header('Location: index.php?msg=Inicia sesión primero');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php?msg=Nota no especificada');
    exit;
}

$noteId = (int)$_GET['id'];
$note = getNoteById($noteId);

if ($note === null) {
    echo "<p>⚠️ Nota no encontrada o no tienes permiso para verla.</p>";
    echo '<a href="dashboard.php">Volver al panel</a>';
    exit;
}

$editMode = isset($_GET['edit']) && $_GET['edit'] == 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title !== '' && $content !== '') {
        updateNote($noteId, $title, $content);
        $note = getNoteById($noteId);
        $editMode = false;
    } else {
        $msg = "⚠️ Título y contenido no pueden estar vacíos";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($note->getTitle() ?? '') ?></title>
    <link rel="stylesheet" href="./assets/style2.css">
</head>
<body>

<header>
    <h1><?= htmlspecialchars($note->getTitle() ?? '') ?></h1>
    <a href="dashboard.php">⬅ Volver al panel</a>
</header>

<main>
    <?php if (isset($msg)) : ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if ($editMode): ?>
        <form method="post">
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($note->getTitle() ?? '') ?>" required>

            <label for="content">Contenido:</label>
            <textarea id="content" name="content" rows="8" required><?= htmlspecialchars($note->getNoteContent() ?? '') ?></textarea>
            <div id="divCancel">
                <a href="verNota.php?id=<?= htmlspecialchars($note->getId()) ?>" style="margin-left: 10px;">Cancelar</a>
                <button type="submit">Guardar cambios</button>
            </div>
        </form>
    <?php else: ?>
        <div class="note-content">
            <p><?= nl2br(htmlspecialchars($note->getNoteContent() ?? '')) ?></p>
            <p class="note-date"><em>Creada el:</em> <?= htmlspecialchars($note->getCreatedAt() ?? '') ?></p>
            <form method="get" style="margin-top: 1em;">
                <input type="hidden" name="id" value="<?= htmlspecialchars($note->getId()) ?>">
                <input type="hidden" name="edit" value="1">
                <div id="divCancel">
                    <p></p>
                    <button type="submit">Editar nota</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
