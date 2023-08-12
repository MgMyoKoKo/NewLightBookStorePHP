<?php
class dbh{
    protected function connect(){
        try {
            $username='root';
            $password='';
            $dbh = new PDO('mysql:host=localhost; dbname=new_light',$username,$password);
            return $dbh;
        } catch (PDOException $e) {
            print "error !". $e->getMessage()."<br>";
            die();
        }

    }
}