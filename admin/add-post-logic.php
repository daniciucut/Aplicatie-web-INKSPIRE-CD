<?php
require 'config/database.php'; //baza de date

require_once '../vendor/autoload.php';//pentru trimiterea pe email

require_once __DIR__ . '/../lang/lang.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use DeepL\Translator;






if (isset($_POST['submit'])) {
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = mysqli_real_escape_string($connection, $_POST['body']);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    
    //daca nu apasam butonul is_featured = 0

    $is_featured = $is_featured == 1 ?: 0;

    //valideaza chestionarul de date

    if (!$title) {
        $_SESSION['add-post'] = t('placeholder_post_title');
    } elseif (!$category_id) {
        $_SESSION['add-post'] = t('select_post_category');
    } elseif (!$body) {
        $_SESSION['add-post'] = t('enter_post_content');
    } elseif (!$thumbnail['name']) {
        $_SESSION['add-post'] = t('select_post_preview_image');
    } else {

        // Lucram la imagine
        // redenumim imaginea
        $time = time(); // facem fiecare imagine unica

        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name;

        // verificam ca fisierul introdus sa fie imagine
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);
        if (in_array($extension, $allowed_files)) {

            // ne asiguram ca imaginea nu este prea mare (2mb+)

            if ($thumbnail['size'] < 2000000) {

                //incarca imaginea de previzualizare
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
                $_SESSION['add-post'] = t('error_file_too_large_2mb');
            }
        } else {
            $_SESSION['add-post'] = t('error_file_format_png_jpg_jpeg');
        }
    }




    

    //redirectioneaza inapoi(cum form ul de date) la add-post daca exista o problema

    if (isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();
    } else {
        //daca aceasta postare va trebuia sa apara prima atunci, celelalte for deveni is_featured=0
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }
            



        // 2. Instantiem DeepL daca nu exista erori de validare
     try {
        $deepl_api_key = 'b243f129-8feb-4e81-b977-359c21cd75f6:fx';
        $translator    = new Translator($deepl_api_key);

        // 2.1. Traduce titlul
        $title_en_result = $translator->translateText($title, 'ro', 'en-US');
        $title_en = mysqli_real_escape_string($connection, $title_en_result->text);

        // 2.2. Traduce continutul, body
        $body_en_result = $translator->translateText($body, 'ro', 'en-US');
        $body_en = mysqli_real_escape_string($connection, $body_en_result->text);
     } catch (DeepLException $e) {
        $_SESSION['add-post'] = t('error_translation_failed') . ': ' . $e->getMessage();
        $_SESSION['add-post-data'] = $_POST;
        header('Location: ' . ROOT_URL . 'admin/add-post.php');
        exit;
     }




        // Introducem in baza de date, cu traducerile aferente
        $query = "INSERT INTO posts (title, title_en, body, body_en, thumbnail, category_id, author_id, is_featured) VALUES ('$title', '$title_en', '$body', '$body_en', '$thumbnail_name', $category_id, $author_id, $is_featured)";
        $result = mysqli_query($connection, $query);

        if (!mysqli_errno($connection)) { //daca totul este ok, continuam :))

            $_SESSION['add-post-success'] = t('post_added_success');

             //aici vom trimite postarile catre toti abonatii
             
              $post_id = mysqli_insert_id($connection);
              $post_url= ROOT_URL . " post.php?id=" . $post_id;

              $mail = new PHPMailer(true);

              //setari SMTP
              $mail->isSMTP();
              $mail->SMTPAuth = true;
              $mail->Host = 'smtp.gmail.com';
              $mail->Username = 'inkspireblog2025@gmail.com';
              $mail->Password = "fccj rrjq tziy bycd";
              $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
              $mail->Port = 587;

              $mail->setFrom('inkspireblog2025@gmail.com', 'Inkspire Blog');
              $mail->Subject = t('new_article_inkspire');

              $mail->isHTML(true);

              $post_title = $title; // ai deja titlul definit sus în formular
              $mail->Body = "
              <h2>{$post_title}</h2>
              <p><?= t('new_article_published') ?></p>

             <a href='" . ROOT_URL . "post.php?id=" . $post_id . "' style='display:inline-block; padding:10px 15px; background-color:#6f6af8; color:#ffffff; border-radius:5px;'><?= t('read_article') ?></a>";
              //PREIA TOTI ABONATI DIN BAZA DE DATE
              $subscribers_result = mysqli_query($connection, "SELECT email FROM subscribers");


              if (mysqli_num_rows($subscribers_result)>0){
                while($subscriber = mysqli_fetch_assoc($subscribers_result)){
                    $mail -> clearAddresses(); //curata adresele precedente
                    $mail -> addAddress($subscriber['email']); //adauga fiecare adresa existenta in baza de date

                    try {
                        $mail ->send();

                    }catch(Exception $e){
                       echo t('error_sending_to') . ' ' . $subscriber['email'] . '. ' . $mail->ErrorInfo;

                    }
                }

                

            




              }


                     
             



            
            
            header('location: ' . ROOT_URL . 'admin/');
            die();
        }
    }
}

header('location: ' . ROOT_URL . 'admin/add-post.php');
die();
