<?php
class GetBookCtrl extends dbh
{
    protected function showBook($search)
    {
        if(!empty($search))
        {
            $stmt=$this->connect()->query("SELECT book.price,book.image,book.book_id,book.title, author.author_name, book.ISBN
                                            FROM book
                                            JOIN author ON book.author_id = author.author_id
                                            WHERE author.author_name LIKE '%$search%' OR book.title LIKE '%$search%' OR book.ISBN LIKE '%$search%'");
            $searchresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $searchresult;
        }
    }

    protected function updateQuery($title, $author_name, $ISBN, $price,$book_id)
    {
        $stmt = $this->connect()->prepare("UPDATE book b, author a 
                                   SET b.title = ?,a.author_name = ?, b.ISBN = ?, b.price = ?  
                                   WHERE b.book_id = ? AND a.author_id = b.author_id");
        if (!$stmt->execute([$title, $author_name, $ISBN, $price, $book_id])) {
            $stmt = null;
            exit;
        }      
    }
    protected function deleteQuery($book_id)
    {
        $stmt = $this->connect()->prepare("DELETE FROM book WHERE book_id = ?");
        if (!$stmt->execute([$book_id])) {
            $stmt = null;
            exit;
        }
    }
}
