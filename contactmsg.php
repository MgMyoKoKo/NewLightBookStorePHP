<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // check if the required fields are set
    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message'])) {
        // required fields are missing, return an error
        http_response_code(400);
        echo 'Error: Please fill out all required fields.';
        exit;
    }

    // sanitize form input
    $name = filter_var($_POST['name'],);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'],);
    $message = filter_var($_POST['message'],);

    // validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // invalid email address, return an error
        http_response_code(400);
        echo 'Error: Invalid email address.';
        exit;
    }

    // configure PHPMailer
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '1aff37e3594021'; // replace with your own email address
        $mail->Password   = 'd7668cd0ab3afd'; // replace with your own email password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 2525;

        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('newlightbookstore@gmail.com', 'From Customer'); // replace with your own recipient email address and name

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = 'Name: ' . $name . '<br>Subject: ' . $subject . '<br>Email: ' . $email . '<br>Message: ' . $message;

        $mail->send();
        echo '<script type="text/javascript"> 
        window.alert("Message sent successfully");
        setTimeout(function() {
            window.location.href = "contactus.php";
        }, 500); // wait for 0.5 seconds before redirecting
      </script>';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo; 
    }
} else {
    // form has not been submitted, return an error
    http_response_code(400);
    echo 'Error: Invalid request.';
    exit;
}
