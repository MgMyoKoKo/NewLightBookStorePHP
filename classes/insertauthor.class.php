<?php

class Author extends dbh
{
    protected function insertAuthor($author_name)
    {
        $stmt = $this->connect()->prepare("INSERT INTO author (author_name) VALUES (?)");
        if (!$stmt->execute([$author_name])) {
            $stmt = null;
            header("Location: ../nlbookstore/insertauthor.php?error=stmt failed");
            exit;
        }
    }

    protected function checkAuthor($author_name)
    {
        $stmt = $this->connect()->prepare("SELECT author_name FROM author WHERE author_name= ?");
        if (!$stmt->execute([$author_name])) {
            $stmt = null;
            exit;
        }

        if ($stmt->rowCount() > 0) {
            $resultcheck = false;
        } else {
            $resultcheck = true;
        }
        return $resultcheck;
    }
}
