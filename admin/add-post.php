<?php
include 'partials/header.php';



$lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

// determina campurile din baza de daet in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';

 //pentru descrierea categoriilor
 $description_field = $lang === 'en' ? 'description_en' : 'description'; 


//incarca categoriile din baza de date

$query = "SELECT * FROM categories";
$categories = mysqli_query($connection, $query);

//luam inapoi tabela de date daca ea este invalida
$title = $_SESSION['add-post-data']['title'] ?? null;
$body = $_SESSION['add-post-data']['body'] ?? null;

//sterge sesiunea form ului curent

unset($_SESSION['add-post-data']);
?>


<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/tk6hvg564535ymi490vxixtxoyey8x5kd959tnk689xohn2w/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea#body',  // Asigura-te că ID-ul este corect
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    height: 400
  });
</script>


<section class="form__section">
    <div class="container form__section-container">
        <h2><?= t('add_a_post') ?></h2>  <!-- Traducerea pentru a adauga o postare -->

        <?php if (isset($_SESSION['add-post'])) : ?>
            <div class="alert__message error">
                <p>
                    <?= $_SESSION['add-post'];
                    unset($_SESSION['add-post']);
                    ?>
                </p>
            </div>
        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/add-post-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" name="title" value="<?= $title ?>" placeholder="<?= t('placeholder_title') ?>">
            <select name="category">
                <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category[$title_field] ?></option>
                <?php endwhile ?>
            </select>
            <textarea id="body" name="body" rows="10"placeholder="<?= t('placeholder_content') ?>"><?= $body ?></textarea>
            <?php if (isset($_SESSION['user_is_admin'])) : ?>
                <div class="form__control inline">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" checked>
                    <label for="is_featured"><?= t('featured') ?></label>
                </div>
            <?php endif ?>
            <div class="form__control">
                <label for="thumbnail"><?= t('add_preview_image') ?></label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <button type="submit" name="submit" class="btn"><?= t('add_the_post') ?></button>
        </form>
    </div>
</section>



<?php
include '../partials/footer.php';
?>