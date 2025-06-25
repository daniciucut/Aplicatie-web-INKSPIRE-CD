<?php
require '../partials/header.php';

//verifica sesiunea de logare
if (!isset($_SESSION['user-id'])) {
    header('location: ' . ROOT_URL . 'signin.php');
    die();
}
