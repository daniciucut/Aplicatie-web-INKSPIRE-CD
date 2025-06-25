<?php
require 'config/constants.php';
// inchide toate sesiunile si redirectioneaza utilizatorul la pagina principala
session_destroy();
header('location: ' . ROOT_URL);
die();
