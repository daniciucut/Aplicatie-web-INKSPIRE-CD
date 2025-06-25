


<li class="lang-switcher-auth">
    <a href="?lang=ro" class="<?= $lang==='ro' ? 'active' : '' ?>">RO</a>
    <span>|</span>
    <a href="?lang=en" class="<?= $lang==='en' ? 'active' : '' ?>">EN</a>
</li>



<?php
// Alegi limba daca sa fie romana/engleza 5
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ro','en'])) {
  $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'ro';

// Încarcă fișierul JSON coresp.
$langFile = __DIR__ . "/../lang/{$lang}.json";
$json = file_get_contents($langFile);
$translations = json_decode($json, true);

// Funcție traduceri
function t($key) {
  global $translations;
  return $translations[$key] ?? $key;
}
?>

<style>
.lang-switcher-auth {
  position: absolute;
  top: 2rem;
  right: 2rem;
  z-index: 10;
  display: flex;
  gap: 0.5rem;
  font-weight: 600;
}
.lang-switcher-auth a {
  color: #fff;
  text-decoration: none;
  transition: color .2s;
}
.lang-switcher-auth a.active,
.lang-switcher-auth a:hover {
  color: #6f6af8;
}
.lang-switcher-auth span {
  color: #aaa;
}
</style>
