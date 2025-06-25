<?php
require 'config/database.php';





//afiseaza utilizatorii din tabela user din baza de date

if (isset($_SESSION['user-id'])) {
    $id = filter_var($_SESSION['user-id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT avatar FROM users WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $avatar = mysqli_fetch_assoc($result);
}
?>

<?php
// Alegi limba daca sa fie romana/engleza 5
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ro','en'])) {
  $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'ro';

// incarca fisierul JSON corespunzator
$langFile = __DIR__ . "/../lang/{$lang}.json";
$json = file_get_contents($langFile);
$translations = json_decode($json, true);

// Funcție traduceri
function t($key) {
  global $translations;
  return $translations[$key] ?? $key;
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP & MySQL Aplicatie intercativa cu continut digital</title>
    <!-- CUSTOM STYLESHEET, stilul designului -->
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
    <!-- ICONSCOUT CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- GOOGLE FONT (MONTSERRAT) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>
    <nav>
        <div class="container nav__container">
            <a href="<?= ROOT_URL ?>" class="nav__logo">INKSPIRE</a>
            <ul class="nav__items">
             <li><a href="<?= ROOT_URL ?>blog.php"><?= t('blog') ?></a></li>
             <li><a href="<?= ROOT_URL ?>about.php"><?= t('about') ?></a></li>
            <!-- <li><a href="<?= ROOT_URL ?>services.php"><?= t('services') ?></a></li>-->
             <li><a href="<?= ROOT_URL ?>contact.php"><?= t('contact') ?></a></li>
                <?php if (isset($_SESSION['user-id'])) : ?>
                    <li class="nav__profile">
                        <div class="avatar">
                            <img src="<?= ROOT_URL . 'images/' . $avatar['avatar'] ?>">
                        </div>
                        <ul>
                            <li><a href="<?= ROOT_URL ?>admin/index.php"><?= t('dashboard') ?></a></li>
 
                            <li><a href="<?= ROOT_URL ?>logout.php"><?= t('logout') ?></a></li>

                        </ul>
                    </li>
                      
                <?php else : ?>
                    <li><a href="<?= ROOT_URL ?>signin.php"><?= t('signin') ?></a></li>

                <?php endif ?>
 
                <?php
                $url = strtok($_SERVER["REQUEST_URI"], '?');
                $params = $_GET;
                unset($params['lang']);
                $query = http_build_query($params);
                $prefix = $query ? '?' . $query . '&' : '?';
                ?>




                <li class="lang-switcher">
                <a href="<?= $url . $prefix ?>lang=ro" class="<?= $lang==='ro' ? 'active' : '' ?>">RO</a>
                <span>|</span>
                <a href="<?= $url . $prefix ?>lang=en" class="<?= $lang==='en' ? 'active' : '' ?>">EN</a>
                </li>
             </ul>

            

            <button id="open__nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>
    <!--====================== sfarsitul NAV ====================-->