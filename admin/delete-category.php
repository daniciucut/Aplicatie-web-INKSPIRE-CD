<?php
require 'config/database.php';
require_once __DIR__ . '/../lang/lang.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // ID-> 1 e ‘Uncategorized’
    $uncategorized_id = 1;

    // pentru mai tarziuu
    // udpate category_id of posts that belong to this category to id of uncategorized category
    $update_query = "UPDATE posts SET category_id= $uncategorized_id WHERE category_id=$id";
    $update_result = mysqli_query($connection, $update_query);

    if (!mysqli_errno($connection)) {
        // delete category
        $query = "DELETE FROM categories WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);
        $_SESSION['delete-category-success'] = t('success_category_deleted');

    }
}
header('location: ' . ROOT_URL . 'admin/manage-categories.php');
die();
