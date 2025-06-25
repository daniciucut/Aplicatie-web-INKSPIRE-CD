<?php
require 'config/constants.php';

require 'lang/lang.php'; // includem modulul de limba


// obtine datele din formular daca a fost o eroare
$firstname = $_SESSION['signup-data']['firstname'] ?? null;
$lastname = $_SESSION['signup-data']['lastname'] ?? null;
$username = $_SESSION['signup-data']['username'] ?? null;
$email = $_SESSION['signup-data']['email'] ?? null;
$createpassword = $_SESSION['signup-data']['createpassword'] ?? null;
$confirmpassword = $_SESSION['signup-data']['confirmpassword'] ?? null;

//sterge dateele din sesiunea de signup
unset($_SESSION['signup-data']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP & MySQL Aplicatie web pentru interactiune si publicare cu continut digital</title>
    <!-- CUSTOM STYLESHEET -->
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
    <!-- ICONSCOUT CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- GOOGLE FONT (MONTSERRAT) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>


    <section class="form__section">
        <div class="container form__section-container">

            <h2><?= t('sign_up_page') ?></h2>
            

            <?php if (isset($_SESSION['signup'])) : ?>
                <div class="alert__message error">
                    <p>
                        <?= $_SESSION['signup'];
                        unset($_SESSION['signup']);
                        ?>
                    </p>
                </div>
            <?php endif ?>
            <form action="<?= ROOT_URL ?>signup-logic.php" enctype="multipart/form-data" method="POST">
                 <input type="text" name="firstname" value="<?= $firstname ?>" placeholder="<?= t('placeholder_firstname') ?>">
                 <input type="text" name="lastname" value="<?= $lastname ?>" placeholder="<?= t('placeholder_lastname') ?>">
                 <input type="text" name="username" value="<?= $username ?>" placeholder="<?= t('placeholder_username') ?>">
                 <input type="email" name="email" value="<?= $email ?>" placeholder="<?= t('placeholder_email_sign_up') ?>">
                 <input type="password" name="createpassword" value="<?= $createpassword ?>" placeholder="<?= t('placeholder_create_password') ?>">
                 <input type="password" name="confirmpassword" value="<?= $confirmpassword ?>" placeholder="<?= t('placeholder_confirm_password') ?>">
                <div class="form__control">
                    <label for="avatar"><?= t('label_avatar') ?></label>
                    <input type="file" name="avatar" id="avatar">
                </div>
                <button type="submit" name="submit" class="btn"><?= t('sign_up_button') ?></button>
                <small><?= t('already_have_account') ?> <a href="signin.php"><?= t('sign_in_sing_up_page') ?></a></small>
            </form>
        </div>
    </section>


</body>

</html>