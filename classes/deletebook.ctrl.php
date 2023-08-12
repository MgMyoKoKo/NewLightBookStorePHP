<?php

class DeleteBook extends GetBookCtrl
{
    private $book_id;


    public function __construct($book_id)
    {
        $this->book_id = $book_id;
    }

    public function delBook()
    {
        return $this->deleteQuery($this->book_id);
    }
}
