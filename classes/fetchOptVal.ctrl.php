<?php
include 'classes/dbh.class.php';
class fetchOptionValue extends dbh
{
    public function getAuthorNames()
    {
        $stmt = $this->connect()->query("SELECT author_id, author_name FROM author");
        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $authors;
    }

    public function getLanguageName()
    {
        $stmt=$this->connect()->query('SELECT language_id,language_name FROM book_language');
        $languages=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $languages;
    }

    public function getPubName()
    {
        $stmt=$this->connect()->query('SELECT publisher_id, publisher_name FROM publisher');
        $publishers=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $publishers;
    }

    public function getCatName()
    {
        $stmt=$this->connect()->query('SELECT category_id, category_name FROM categories');
        $categories=$stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }
}