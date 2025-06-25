<?php
require 'config/database.php';
require_once __DIR__ . '/../lang/lang.php'; 

// Obține datele formularului dacă butonul de trimitere a fost apăsat

if (isset($_POST['submit'])) {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);
    $avatar = $_FILES['avatar'];

    //validam datele introduse

    if (!$firstname) {
        $_SESSION['add-user'] = t('error_enter_firstname');

    } elseif (!$lastname) {
        $_SESSION['add-user'] = t('error_enter_lastname');

    } elseif (!$username) {
        $_SESSION['add-user'] = t('error_enter_username');

    } elseif (!$email) {
        $_SESSION['add-user'] = t('error_enter_valid_email');

    } elseif (strlen($createpassword) < 8 || strlen($confirmpassword) < 8) {
        $_SESSION['add-user'] = t('error_password_min_length');

    } elseif (!$avatar['name']) {
        $_SESSION['add-user'] = t('error_upload_profile_photo');

    } else {

        //verifica daca parolele se potrivesc
        if ($createpassword !== $confirmpassword) {
            $_SESSION['add-user'] = t('error_passwords_do_not_match');


        } else {
            // hash parola
            $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);

            //verica daca exista deja in baza de date username sau email
            $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if (mysqli_num_rows($user_check_result) > 0) {
                $_SESSION['add-user'] = t('error_username_email_exists');

            } else {
                // WORK ON AVATAR
                // rename avatar
                $time = time(); // make each image name unique using current timestamp
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path = '../images/' . $avatar_name;

                // make sure file is an image
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extention = explode('.', $avatar_name);
                $extention = end($extention);
                if (in_array($extention, $allowed_files)) {
                    // make sure image is not too large (1mb+)
                    if ($avatar['size'] < 1000000) {
                        // upload avatar
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    } else {
                        $_SESSION['add-user'] = t('error_file_too_large_1mb');

                    }
                } else {
                    $_SESSION['add-user'] = t('error_file_format_png_jpg_jpeg');

                }
            }
        }
    }

    // redirect back to add-user pag eif there was any problem
    if (isset($_SESSION['add-user'])) {
        // pass form data back to sigup page
        $_SESSION['add-user-data'] = $_POST;
        header('location: ' . ROOT_URL . '/admin/add-user.php');
        die();
    } else {
        // insert new user into users table
        $insert_user_query = "INSERT INTO users SET firstname='$firstname', lastname='$lastname', username='$username', email='$email', password='$hashed_password', avatar='$avatar_name', is_admin=$is_admin";
        $insert_user_result = mysqli_query($connection, $insert_user_query);

        if (!mysqli_errno($connection)) {
            // redirect to login page with success message
            $_SESSION['add-user-success'] = sprintf(t('success_new_user_added'), $firstname, $lastname);

            header('location: ' . ROOT_URL . 'admin/manage-users.php');
            die();
        }
    }
} else {
    // if button wasn't clicked, bounce back to signup page
    header('location: ' . ROOT_URL . 'admin/add-user.php');
    die();
}
