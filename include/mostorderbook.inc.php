<?php

if (isset($_POST['submit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    include '../classes/dbh.class.php';
    include '../classes/mostorderbook.class.php';
    include '../classes/mostorderbook.ctrl.php';


    try {
        $filterdate = new MostOrderBookCtrl($start_date, $end_date);
        $result = $filterdate->getMOB();


        // Store the result array in a session variable
        session_start();
        $_SESSION['result'] = $result;

        // Redirect to the results page
        if (!empty($result)) {
            $result_url = '/nlbookstore/mostorderbook.php';
            header('Location: ' . $result_url);
            exit();
        } else {
            echo 'No results found.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}  elseif (isset($_POST['submitleast'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    include '../classes/dbh.class.php';
    include '../classes/mostorderbook.class.php';
    include '../classes/mostorderbook.ctrl.php';


    try {
        $filterdate = new MostOrderBookCtrl($start_date, $end_date);
        $result = $filterdate->leastMOB();
        

        // Store the result array in a session variable
        session_start();
        $_SESSION['result'] = $result;

        // Redirect to the results page
        if (!empty($result)) {
            $result_url = '/nlbookstore/leastorderbook.php';
            header('Location: ' . $result_url);
            exit();
        } else {
            echo 'No results found.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} elseif (isset($_POST['submittop'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    include '../classes/dbh.class.php';
    include '../classes/mostorderbook.class.php';
    include '../classes/mostorderbook.ctrl.php';


    try {
        $filterdate = new MostOrderBookCtrl($start_date, $end_date);
        $result = $filterdate->topCmr();
        

        // Store the result array in a session variable
        session_start();
        $_SESSION['result'] = $result;

        // Redirect to the results page
        if (!empty($result)) {
            $result_url = '/nlbookstore/topcustomer.php';
            header('Location: ' . $result_url);
            exit();
        } else {
            echo 'No results found.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} elseif (isset($_POST['submitcat'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    include '../classes/dbh.class.php';
    include '../classes/mostorderbook.class.php';
    include '../classes/mostorderbook.ctrl.php';


    try {
        $filterdate = new MostOrderBookCtrl($start_date, $end_date);
        $result = $filterdate->topCat();
        

        // Store the result array in a session variable
        session_start();
        $_SESSION['result'] = $result;

        // Redirect to the results page
        if (!empty($result)) {
            $result_url = '/nlbookstore/topcategory.php';
            header('Location: ' . $result_url);
            exit();
        } else {
            echo 'No results found.';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

