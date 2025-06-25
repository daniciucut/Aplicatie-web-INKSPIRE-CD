<?php

//folosind metoda unui smtp extern(PHPMailer)


$name = $_POST["name"];
$email = $_POST["email"];
$subject = $_POST["subject"];
$message = $_POST ["message"];

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



// cream o noua instanta 

$mail =  new PHPMailer(true); //va configura sa arunce o exceptie daca este o problema 




// setari stmp

$mail -> isSMTP();

$mail -> SMTPAuth = true;

$mail -> Host = " smtp.gmail.com";

$mail -> SMTPSecure = PHPMailer :: ENCRYPTION_STARTTLS;

$mail -> Port = 587;

$mail-> Username =  "inkspireblog2025@gmail.com";

$mail-> Password = "fccj rrjq tziy bycd";


//setari expeditor

$mail->setFrom('inkspireblog2025@gmail.com', 'Inkspire App');

$mail->addAddress('inkspireblog2025@gmail.com', 'Daniel Cucicea');

$mail->addReplyTo($email, $name);

$mail->Subject = "Mesaj nou de contact: $subject";

    //  HTML pentru aspect 
    $mail->isHTML(true);
    $mail->Body = "
        <h3>Mesaj nou de la: $name</h3>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Subiect:</strong> $subject</p>
        <p>$message</p>
        <br>
        <small>Trimis automat prin formularul de contact Inkspire Blog.</small>
    ";

    // Trimiterea mesajului
    if ($mail->send()) {
        echo "Mesajul tău a fost trimis cu succes! Te vom contacta în curând.";
    } else {
        echo "Eroare la trimiterea mesajului: {$mail->ErrorInfo}";
    }

 

?>



