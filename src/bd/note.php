<?php
    final class Note{
        private $id;
        private $userId;
        private $title;
        private $noteContent;
        private $createdAt;
    


        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

        public function getTitle() {
            return $this->title;
        }

        public function setTitle($title) {
            $this->title = $title;
        }

        public function getNoteContent() {
            return $this->noteContent;
        }

        public function setNoteContent($note_content) {
            $this->noteContent = $note_content;
        }

        public function getCreatedAt() {
            return $this->createdAt;
        }

        public function setCreatedAt($createdAt) {
            $this->createdAt = $createdAt;
        }
    }
?>