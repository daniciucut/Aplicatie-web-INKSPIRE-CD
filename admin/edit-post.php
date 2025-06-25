<?php
include 'partials/header.php';

 $lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

 // determina campurile din baza de date in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';
 //pentru descrierea categoriilor
 $description_field = $lang === 'en' ? 'description_en' : 'description'; 






// fetch categories from database
$category_query = "SELECT * FROM categories";
$categories = mysqli_query($connection, $category_query);


// fetch post data from database if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($result);
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/tk6hvg564535ymi490vxixtxoyey8x5kd959tnk689xohn2w/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea#body',  // Aplicăm TinyMCE pe textarea-ul pentru conținut
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    height: 400
  });
</script>



<section class="form__section">
    <div class="container form__section-container">

        <h2><?= t('edit_post') ?></h2>

        <form action="<?= ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="id" value="<?= $post['id'] ?>">
            <input type="hidden" name="previous_thumbnail_name" value="<?= $post['thumbnail'] ?>">
            <input type="text" name="<?= $title_field ?>" value="<?= $post[$title_field] ?>" placeholder="<?= t('placeholder_title') ?>">
            <select name="category">
                <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category[$title_field] ?></option>
                <?php endwhile ?>
            </select>
            <textarea id="body" name="<?= $body_field ?>"><?= $post[$body_field] ?></textarea>
            <div class="form__control inline">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" checked>
                <label for="is_featured"><?= t('featured') ?></label>
            </div>
            <div class="form__control">
                <label for="thumbnail"><?= t('change_thumbnail') ?></label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <button type="submit" name="submit" class="btn"><?= t('update_post') ?></button>
        </form>
    </div>
</section>


<?php
include '../partials/footer.php';
?>