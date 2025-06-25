<?php
 require 'config/database.php';

 require_once __DIR__ . '/../lang/lang.php'; 
 require_once __DIR__ . '/../vendor/autoload.php';

 use DeepL\Translator;



 



if (isset($_POST['submit'])) {
    
    // Obtine datele formularului

    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$title) {
        $_SESSION['add-category'] = t('enter_title');

    } elseif (!$description) {
        $_SESSION['add-category'] = t('enter_description');

    }

    // Redirecționează înapoi la pagina de adăugare a categoriei cu datele formularului dacă există o introducere nevalidă

    if (isset($_SESSION['add-category'])) {
        $_SESSION['add-category-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-category.php');
        die();
    } else {


      // 2. Instantiem DeepL daca nu exista erori de validare
     try {
        $deepl_api_key = 'b243f129-8feb-4e81-b977-359c21cd75f6:fx';
        $translator    = new Translator($deepl_api_key);

        // 2.1. Traduce titlul
        $title_en_result = $translator->translateText($title, 'ro', 'en-US');
        $title_en = mysqli_real_escape_string($connection, $title_en_result->text);

        // 2.2. Traduce continutul, body
        $description_en_rezult = $translator->translateText($description, 'ro', 'en-US');
        $description_en = mysqli_real_escape_string($connection, $description_en_rezult->text);
     } catch (DeepLException $e) {
        $_SESSION['add-post'] = t('error_translation_failed') . ': ' . $e->getMessage();
        $_SESSION['add-post-data'] = $_POST;
        header('Location: ' . ROOT_URL . 'admin/add-post.php');
        exit;
     }
        

        //adauga categoria in baza de date
        $query = "INSERT INTO categories (title, title_en, description, description_en) VALUES ('$title', '$title_en','$description', '$description_en')";
        $result = mysqli_query($connection, $query);
        if (mysqli_errno($connection)) {
            $_SESSION['add-category'] = t('error_could_not_add_category');

            header('location: ' . ROOT_URL . 'admin/add-category.php');
            die();
        } else {
            $_SESSION['add-category-success'] = sprintf(t('success_category_added'), $title);
            header('location: ' . ROOT_URL . 'admin/manage-categories.php');
            die();
        }
    }
}
