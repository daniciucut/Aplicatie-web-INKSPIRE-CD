<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$subscribeMessage = "";

if(isset($_POST['subscribe'])){
    $userEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if(filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
        $stmt = $connection->prepare("SELECT id FROM subscribers WHERE email=?");
        $stmt->bind_param("s",$userEmail);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows == 0){
            $insert = $connection->prepare("INSERT INTO subscribers(email) VALUES(?)");
            $insert->bind_param("s", $userEmail);
            if($insert->execute()){
                // Trimite email cu PHPMailer
                $mail = new PHPMailer(true);
                try{
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Username = 'inkspireblog2025@gmail.com';
                    $mail->Password = "fccj rrjq tziy bycd";
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('inkspireblog2025@gmail.com', 'Inkspire Blog');
                    $mail->addAddress($userEmail);
                    $mail->Subject = "Subscription Confirmed!";
                    $mail->Body = "Thanks for subscribing to Inkspire Blog!";

                    $mail->send();
                    $subscribeMessage = "Te-ai abonat cu succes! Mulțumim!";
                } catch(Exception $e){
                    $subscribeMessage = "Te-ai abonat, dar emailul nu a putut fi trimis.";
                }
            } else{
                $subscribeMessage = "Eroare la baza de date!";
            }
        } else {
            $subscribeMessage = "Acest email este deja abonat!";
        }
    } else {
        $subscribeMessage = "Adresa de email nu este validă!";
    }
}
?>
