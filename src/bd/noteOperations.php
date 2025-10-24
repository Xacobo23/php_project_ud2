<?php
session_start();
require 'conection.php';
require 'note.php';
$username = $_SESSION["username"];

function getAllNotes(): array {
    global $conn, $username;
    $stmt = $conn->prepare("
        SELECT * FROM notas 
        WHERE user_id = (SELECT id FROM usuarios WHERE username = :user)
        ORDER BY created_at DESC
    ");
    $stmt->bindParam(":user", $username);
    $stmt->execute();

    $noteList = array();
    while($element = $stmt->fetch(PDO::FETCH_ASSOC)){
        $note = new Note();
        $note->setId($element["id"]);
        $note->setUserId($element["user_id"]);
        $note->setTitle($element["title"]);
        $note->setNoteContent($element["content"]);
        $note->setCreatedAt($element["created_at"]);
        $noteList[] = $note;
    }

    return $noteList;
}

function getNoteById($id) {
    global $conn, $username;
    $stmt = $conn->prepare("
        SELECT * FROM notas 
        WHERE id = :id
        AND user_id = (SELECT id FROM usuarios WHERE username = :user)
        LIMIT 1
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user', $username);
    $stmt->execute();

    $noteData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($noteData) {
        $note = new Note();
        $note->setId($noteData['id']);
        $note->setUserId($noteData['user_id']);
        $note->setTitle($noteData['title']);
        $note->setNoteContent($noteData['content']);
        $note->setCreatedAt($noteData['created_at']);
        return $note;
    } else {
        return null;
    }
}

function createNote($title, $content) {
    global $conn, $username;
    $stmt = $conn->prepare("
        INSERT INTO notas (user_id, title, content, created_at)
        VALUES ((SELECT id FROM usuarios WHERE username = :user), :title, :content, CURRENT_TIMESTAMP)
    ");
    $stmt->bindParam(':user', $username);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    return $stmt->execute();
}

function updateNote($id, $title, $content) {
    global $conn, $username;
    $stmt = $conn->prepare("
        UPDATE notas 
        SET title = :title, content = :content
        WHERE id = :id
        AND user_id = (SELECT id FROM usuarios WHERE username = :user)
    ");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user', $username);
    return $stmt->execute();
}

function deleteNote($id) {
    global $conn, $username;
    $stmt = $conn->prepare("
        DELETE FROM notas 
        WHERE id = :id
        AND user_id = (SELECT id FROM usuarios WHERE username = :user)
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user', $username);
    return $stmt->execute();
}
?>
