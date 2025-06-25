<?php
require 'config/database.php';
require 'lang/lang.php';

if (isset($_POST['submit'])) {
    
    //obtine detaliile din form ul  de logare

    $username_email = filter_var($_POST['username_email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username_email) {
        $_SESSION['signin'] = t('error_username_email_required');
    } elseif (!$password) {
        $_SESSION['signin'] = t('error_password_required');
    } else {

        //aduce utilizatorii din baza de date
        $fetch_user_query = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if (mysqli_num_rows($fetch_user_result) == 1) {

            // converteste record ul intr un assoc vector

            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];
            
            //se compara parola cu cea sin baza de date
            
            if (password_verify($password, $db_password)) {
                
                //seteaza sesiunea pentru acces control

                $_SESSION['user-id'] = $user_record['id'];

                //seteaza sesiunea daca userul este de tip admin
                if ($user_record['is_admin'] == 1) {
                    $_SESSION['user_is_admin'] = true;
                }

                // logare utilizator
                header('location: ' . ROOT_URL . 'admin/');
            } else {
                $_SESSION['signin'] = t('error_check_details');

            }
        } else {
            $_SESSION['signin'] = t('error_user_not_found');

        }
    }
    

    //daca apar probleme redirectioneaza use-ul catre pagina de log-in 
    if (isset($_SESSION['signin'])) {
        $_SESSION['signin-data'] = $_POST;
        header('location: ' . ROOT_URL . 'signin.php');
        die();
    }
} else {
    header('location: ' . ROOT_URL . 'signin.php');
    die();
}
