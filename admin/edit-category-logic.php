<?php
require 'config/database.php';
require_once __DIR__ . '/../lang/lang.php'; 

 $lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

 // determina campurile din baza de daet in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';

 //pentru descrierea categoriilor
 $description_field = $lang === 'en' ? 'description_en' : 'description'; 


if (isset($_POST['submit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST[$title_field], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST[$description_field], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // validare data input
    if (!$title || !$description) {
        $_SESSION['edit-category'] = t('error_invalid_form_edit_category');

    } else {
        $query = "UPDATE categories SET $title_field='$title', $description_field ='$description' WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);

        if (mysqli_errno($connection)) {
            $_SESSION['edit-category'] = t('error_could_not_update_category');

        } else {
            $_SESSION['edit-category-success'] = sprintf(t('success_category_updated'), $title);
        }
    }
}

header('location: ' . ROOT_URL . 'admin/manage-categories.php');
die();
