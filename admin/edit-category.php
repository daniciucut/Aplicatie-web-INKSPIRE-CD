<?php
include 'partials/header.php';


$lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

 // determina campurile din baza de daet in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';

 //pentru descrierea categoriilor
 $description_field = $lang === 'en' ? 'description_en' : 'description'; 

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // fetch category from database
    $query = "SELECT * FROM categories WHERE id=$id";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) == 1) {
        $category = mysqli_fetch_assoc($result);
    }
} else {
    header('location: ' . ROOT_URL . 'admin/manage-categories');
    die();
}
?>


<section class="form__section">
    <div class="container form__section-container">
        <h2><?= t('edit_category') ?></h2>

        <form action="<?= ROOT_URL ?>admin/edit-category-logic.php" method="POST">
            <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <input type="text" name="<?= $title_field ?>" value="<?= $category[$title_field] ?>" placeholder="<?= t('placeholder_title') ?>">
            <textarea rows="4" name="<?= $description_field?>" placeholder="<?= t('placeholder_description') ?>"><?= $category[$description_field] ?></textarea>
            <button type="submit" name="submit" class="btn"><?= t('update_category') ?></button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>