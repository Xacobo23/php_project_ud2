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

// MODO EDICIÓN
$editMode = isset($_GET['edit']) && $_GET['edit'] == 1;

// SI SE ENVÍA EL FORMULARIO (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title !== '' && $content !== '') {
        updateNote($noteId, $title, $content);
        // Recargamos la nota actualizada
        $note = getNoteById($noteId);
        $editMode = false; // Volvemos a modo visualización
        $msg = "✅ Nota actualizada correctamente";
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
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 2em; }
        header { margin-bottom: 1.5em; }
        h1 { color: #333; }
        a, button { display: inline-block; margin-top: 10px; color: #0066cc; text-decoration: none; }
        button { background-color: #0066cc; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #004c99; }
        .note-content, form { background-color: #fff; padding: 1em; border-radius: 6px; box-shadow: 0 0 5px rgba(0,0,0,0.1); max-width: 600px; }
        input[type="text"], textarea { width: 100%; padding: 8px; margin-top: 6px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 4px; }
        .note-date { font-size: 0.9em; color: #666; margin-top: 1em; }
        .msg { padding: 10px; background-color: #e0ffe0; border: 1px solid #00cc00; margin-bottom: 15px; border-radius: 4px; }
    </style>
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

            <button type="submit">💾 Guardar cambios</button>
            <a href="verNota.php?id=<?= htmlspecialchars($note->getId()) ?>" style="margin-left: 10px;">✖ Cancelar</a>
        </form>
    <?php else: ?>
        <div class="note-content">
            <p><?= nl2br(htmlspecialchars($note->getNoteContent() ?? '')) ?></p>
            <p class="note-date"><em>Creada el:</em> <?= htmlspecialchars($note->getCreatedAt() ?? '') ?></p>
            <form method="get" style="margin-top: 1em;">
                <input type="hidden" name="id" value="<?= htmlspecialchars($note->getId()) ?>">
                <input type="hidden" name="edit" value="1">
                <button type="submit">✏️ Editar nota</button>
            </form>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
