<?php

class insertBookCtrl extends insertNewBook
{
    private $authorName;
    private $bookTitle;
    private $isbn;        
    private $language;
    private $numPages;
    private $pubDate;
    private $pubName;
    private $categoryName;
    private $price;
    private $bookImage;
    private $summary;
    private $tempname;
    private $targetfolder;

    public function __construct($authorName, $bookTitle, $isbn, $language, $numPages, $pubDate, $pubName, $categoryName, $price, $bookImage, $summary,$tempname,$targetfolder)
    {
        $this->authorName=$authorName;
        $this->bookTitle = $bookTitle;
        $this->isbn = $isbn;
        $this->language = $language;
        $this->numPages = $numPages;
        $this->pubDate = $pubDate;
        $this->pubName = $pubName;
        $this->categoryName = $categoryName;
        $this->price = $price;
        $this->bookImage = $bookImage;
        $this->summary = $summary;
        $this->tempname = $tempname;
        $this->targetfolder = $targetfolder;

    }

    public function bookRegister()
    {
        if($this->bookDuplicateCheck()==false)
        {
            echo '<script type="text/javascript"> 
        window.alert("Duplicate book Found !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/insertbook.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';
            exit();
        }
        $this->insertBook($this->authorName, $this->bookTitle, $this->isbn, $this->language, $this->numPages, $this->pubDate, $this->pubName, $this->categoryName, $this->price, $this->bookImage, $this->summary,$this->targetfolder,$this->tempname);
    }


    public function bookDuplicateCheck()
    {
       
        if (!$this->isbnCheck($this->isbn)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
    
}