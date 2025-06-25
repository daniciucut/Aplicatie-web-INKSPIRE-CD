<?php
require 'config/database.php';

require 'lang/lang.php';


//obtine datele formularului de inregistrare daca butonul a fost apasat
// 
if (isset($_POST['submit'])) {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];

    // valideaza datele introduse
    if (!$firstname) {
        $_SESSION['signup'] = t('error_firstname_required');
    } elseif (!$lastname) {
        $_SESSION['signup'] = t('error_lastname_required');

    } elseif (!$username) {
        $_SESSION['signup'] = t('error_username_required');

    } elseif (!$email) {
        $_SESSION['signup'] = t('error_valid_email_required');

    } elseif (strlen($createpassword) < 8 || strlen($confirmpassword) < 8) {
        $_SESSION['signup'] = t('error_password_min_length');

    } elseif (!$avatar['name']) {
        $_SESSION['signup'] = t('error_avatar_required');

    } else {
        // verifica daca parolele coincid
        if ($createpassword !== $confirmpassword) {
            $_SESSION['signup'] = t('error_passwords_do_not_match');

        } else {
            // hash password
            $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);

            
            // vefifica daca numele si email exista deja in BD

            $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if (mysqli_num_rows($user_check_result) > 0) {
                $_SESSION['signup'] = t('error_username_email_exists');

            } else {
                // lucram la avatar 
                // nume  avatar
                $time = time(); // make each image name unique using current timestamp
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path = 'images/' . $avatar_name;

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
                        $_SESSION['signup'] = t('error_file_too_large');

                    }
                } else {
                    $_SESSION['signup'] = t('error_invalid_file_format');

                }
            }
        }
    }

    // redirect back to signup pag eif there was any problem
    if (isset($_SESSION['signup'])) {
        // pass form data back to sigup page
        $_SESSION['signup-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signup.php');
        die();
    } else {
        // insert new user into users table
        $insert_user_query = "INSERT INTO users SET firstname='$firstname', lastname='$lastname', username='$username', email='$email', password='$hashed_password', avatar='$avatar_name', is_admin=0";
        $insert_user_result = mysqli_query($connection, $insert_user_query);

        if (!mysqli_errno($connection)) {
            // redirect to login page with success message
            $_SESSION['signup-success'] = t('success_registration');

            header('location: ' . ROOT_URL . 'signin.php');
            die();
        }
    }
} else {
    // if button wasn't clicked, bounce back to signup page
    //inapoi with all the data 
    header('location: ' . ROOT_URL . 'signup.php');
    die();
}
