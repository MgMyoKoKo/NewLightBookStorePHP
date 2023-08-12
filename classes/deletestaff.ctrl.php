<?php

class GetStaff extends Staff
{
    private $search;


    public function __construct($search)
    {
        $this->search = $search;
    }

    public function getStaffResult()
    {
        return $this->showStaff($this->search);
    }
}
