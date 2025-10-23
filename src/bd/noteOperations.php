<?php
session_start();
require 'conection.php';
require 'note.php';
$username = $_SESSION["username"];

function getAllNotes(): array {
    global $conn, $username;
    $stmt = $conn->prepare("
    select * from notas where user_id = 
        (select id from usuarios where username = :user)
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


function getNoteByID($noteId) {
    
}

?>