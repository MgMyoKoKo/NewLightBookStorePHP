<?php

class LoginCtrl extends Staff
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {

        $this->email = $email;
        $this->password = $password;
    }

    public function login()
    {
        if ($this->checkUserEmail() == true) {
            echo '<script type="text/javascript"> 
        window.alert("Staff Not Found, Please Register First !");
        setTimeout(function() {
            window.location.href = "/nlbookstore/adminsignup.php";
        }, 100); // wait for 0.5 seconds before redirecting
      </script>';
            exit();
        }
       return $this->userLogin($this->email, $this->password);
    }

    public function checkUserEmail()
    {
        if (!$this->StaffCheck($this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

}