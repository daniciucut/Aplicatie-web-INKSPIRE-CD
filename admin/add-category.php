<?php
include 'partials/header.php';

// Recuperează datele formularului dacă introducerea este invalida


$title = $_SESSION['add-category-data']['title'] ?? null;
$description = $_SESSION['add-category-data']['description'] ?? null;

unset($_SESSION['add-category-data']);
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2><?= t('add_a_category') ?></h2>

        <?php if (isset($_SESSION['add-category'])) : ?>
            <div class="alert__message error">
                <p>
                    <?= $_SESSION['add-category'];
                    unset($_SESSION['add-category']) ?>
                </p>
            </div>
        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/add-category-logic.php" method="POST">
            <input type="text" value="<?= $title ?>" name="title" placeholder="<?= t('placeholder_title') ?>">
            <textarea rows="4" value="<?= $description ?>" name="description" placeholder="<?= t('placeholder_description') ?>"></textarea>
            <button type="submit" name="submit" class="btn"><?= t('add_the_category') ?></button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>