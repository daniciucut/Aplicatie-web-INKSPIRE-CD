<?php
include 'partials/header.php';

// get back form data if there was an error
$firstname = $_SESSION['add-user-data']['firstname'] ?? null;
$lastname = $_SESSION['add-user-data']['lastname'] ?? null;
$username = $_SESSION['add-user-data']['username'] ?? null;
$email = $_SESSION['add-user-data']['email'] ?? null;
$createpassword = $_SESSION['add-user-data']['createpassword'] ?? null;
$confirmpassword = $_SESSION['add-user-data']['confirmpassword'] ?? null;

// delete session data
unset($_SESSION['add-user-data']);
?>



<section class="form__section">
    <div class="container form__section-container">

        <h2><?= t('add_user') ?></h2>

        <?php if (isset($_SESSION['add-user'])) : ?>
            <div class="alert__message error">
                <p>
                    <?= $_SESSION['add-user'];
                    unset($_SESSION['add-user']);
                    ?>
                </p>
            </div>

        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/add-user-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" name="firstname" value="<?= $firstname ?>"placeholder="<?= t('placeholder_firstname') ?>">
            <input type="text" name="lastname" value="<?= $lastname ?>" placeholder="<?= t('placeholder_lastname') ?>">
            <input type="text" name="username" value="<?= $username ?>" placeholder="<?= t('placeholder_username') ?>">
            <input type="email" name="email" value="<?= $email ?>" placeholder="<?= t('placeholder_email') ?>">
            <input type="password" name="createpassword" value="<?= $createpassword ?>" placeholder="<?= t('placeholder_create_password') ?>">
            <input type="password" name="confirmpassword" value="<?= $confirmpassword ?>" placeholder="<?= t('placeholder_confirm_password') ?>">
            <select name="userrole">
                <option value="0"><?= t('author') ?></option>
                <option value="1"><?= t('admin') ?></option>
            </select>
            <div class="form__control">
                <label for="avatar"><?= t('user_photo') ?></label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name="submit" class="btn"><?= t('add_user') ?></button>
        </form>
    </div>
</section>



<?php
include '../partials/footer.php';
?>