<?php

class GetBook extends GetBookCtrl
{
    private $search;


    public function __construct($search)
    {
        $this->search = $search;
    
    }

    public function getBookResult()
    {
       return $this->showBook($this->search);
    }

  
}
