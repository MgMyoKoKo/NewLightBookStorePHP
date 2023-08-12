<?php

class insertNewBook extends dbh 
{
     protected function getCategoryID($categoryName)
    {

        $stmt = $this->connect()->prepare("SELECT category_id FROM categories WHERE category_name = ?");
        $stmt->execute([$categoryName]);
        $category = $stmt->fetch();
        $category_id = $category['category_id'];
        return $category_id;
    }
  protected function insertBook($author_id, $bookTitle, $isbn, $language, $numPages, $pubDate, $pubName, $category_id, $price, $bookImage, $summary,$targetfolder,$tempname)
  {
    $stmt=$this->connect()->prepare("INSERT INTO book (`author_id`, `title`, `isbn`, `language_id`, `num_pages`, `publication_date`, `publisher_id`, `category_id`, `price`, 
                                    `image`, `description`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        if (!$stmt->execute(array($author_id, $bookTitle, $isbn, $language, $numPages, $pubDate, $pubName,$category_id, $price, $bookImage, $summary))) 
        {
            $stmt = null;
            header("../nlbookstore/insertbook.php?error=stmt failed");
            exit;
        }

        move_uploaded_file($tempname, "$targetfolder/$bookImage");
        
        $stmt = null;
  }  

  protected function isbnCheck($isbn)
  {
        $stmt = $this->connect()->prepare("SELECT isbn FROM book WHERE isbn = ?");
        if (!$stmt->execute([$isbn])) {
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