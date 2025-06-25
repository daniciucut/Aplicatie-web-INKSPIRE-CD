<?php
require 'config/database.php';
require_once __DIR__ . '/../lang/lang.php'; 

 $lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

 // determina campurile din baza de daet in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';


 // verificam daca butonul de editare a fost apasat (nu este NULL)
if (isset($_POST['submit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST[$title_field], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = mysqli_real_escape_string($connection, $_POST[$body_field]);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // set is_featured to 0 if it was unchecked
    $is_featured = $is_featured == 1 ?: 0;

    // check and validate input values
    if (!$title) {
        $_SESSION['edit-post'] = t('error_update_post_invalid_form');

    } elseif (!$category_id) {
        $_SESSION['edit-post'] = t('error_update_post_invalid_form');

    } elseif (!$body) {
        $_SESSION['edit-post'] = t('error_update_post_invalid_form');

    } else {
        // delete existing thumbnail if new thumbail is available
        if ($thumbnail['name']) {
            $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
            if ($previous_thumbnail_path) {
                unlink($previous_thumbnail_path);
            }

            // WORK ON NEW THUMBNAIL
            // Rename image
            $time = time(); // make each image name upload unique using current timestamp
            $thumbnail_name = $time . $thumbnail['name'];
            $thumbnail_tmp_name = $thumbnail['tmp_name'];
            $thumbnail_destination_path = '../images/' . $thumbnail_name;

            // make sure file is an image
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $thumbnail_name);
            $extension = end($extension);
            if (in_array($extension, $allowed_files)) {
                // make sure avatar is not too large (2mb+)
                if ($thumbnail['size'] < 2000000) {
                    // upload avatar
                    move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
                } else {
                    $_SESSION['edit-post'] = t('error_update_post_thumbnail_too_big');
                }
            } else {
                $_SESSION['edit-post'] = t('error_update_post_thumbnail_format');

            }
        }
    }


    if ($_SESSION['edit-post']) {
        // redirect to manage form page if form was invalid
        header('location: ' . ROOT_URL . 'admin/');
        die();
    } else {
        // set is_featured of all posts to 0 if is_featured for this post is 1
        if ($is_featured == 1) {
            $zero_all_is_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_is_featured_result = mysqli_query($connection, $zero_all_is_featured_query);
        }

        // set thumbnail name if a new one was uploaded, else keep old thumbnail name
        $thumbnail_to_insert = $thumbnail_name ?? $previous_thumbnail_name;

        $query = "UPDATE posts SET $title_field ='$title', $body_field='$body', thumbnail='$thumbnail_to_insert', category_id=$category_id, is_featured=$is_featured WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);
    }


    if (!mysqli_errno($connection)) {
        $_SESSION['edit-post-success'] = t('post_updated_success');

    }
}

header('location: ' . ROOT_URL . 'admin/');
die();
