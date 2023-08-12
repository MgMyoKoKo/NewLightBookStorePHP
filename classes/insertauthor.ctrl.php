<?php

class InsertAuthorCtrl extends Author
{
    private $author_name;

    public function __construct($author_name)
    {
        $this->author_name=$author_name;
    }

    public function insertAuthorName()
    {
        if ($this->duplicateAuthorCheck() == false) {
            echo '<script type="text/javascript"> 
        window.alert("Duplicate Author Found !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/insertauthor.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';
            exit();
        }
        $this->insertAuthor($this->author_name);
    }

    public function duplicateAuthorCheck()
    {
        if (!$this->checkAuthor($this->author_name)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}