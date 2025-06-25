<?php
require 'partials/header.php';


 $lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

 // determina campurile din baza de daet in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';






if (isset($_GET['search']) && isset($_GET['submit'])) {
    $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "SELECT * FROM posts WHERE $title_field LIKE '%$search%' ORDER BY date_time DESC";
    $posts = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}
?>


<!-- Vom afisa postarile, daca ele exista-->


<?php if (mysqli_num_rows($posts) > 0) : ?>
    <section class="posts section__extra-margin">
        <div class="container posts__container">
            <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="./images/<?= $post['thumbnail'] ?>">
                    </div>
                    <div class="post__info">
                        <?php

                        //afiseaza categoriile din tabela categories folosind category_id a postari

                        $category_id = $post['category_id'];
                        $category_query = "SELECT * FROM categories WHERE id=$category_id";
                        $category_result = mysqli_query($connection, $category_query);
                        $category = mysqli_fetch_assoc($category_result);
                        ?>
                        <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category__button"><?= $category['title'] ?></a>
                        <h3 class="post__title">
                            <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= $post[$title_field] ?></a>
                        </h3>
                        <p class="post__body">
                            <?= substr($post[$body_field], 0, 150) ?>...
                        </p>
                        <div class="post__author">
                            <?php

                            //afiseaza autorul din tabela users folosind author_id

                            $author_id = $post['author_id'];
                            $author_query = "SELECT * FROM users WHERE id=$author_id";
                            $author_result = mysqli_query($connection, $author_query);
                            $author = mysqli_fetch_assoc($author_result);

                            ?>
                            <div class="post__author-avatar">
                                <img src="./images/<?= $author['avatar'] ?>">
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
                                <small>
                                    <?= date("M d, Y - H:i", strtotime($post['date_time'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile ?>
        </div>
    </section>
<?php else : ?>
    <div class="alert__message error lg section__extra-margin">

        <p><?= t('no_search_posts_found') ?></p>

    </div>
<?php endif ?>
<!--====================== sfarsitul postarilor ====================-->



<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php
        $all_categories_query = "SELECT * FROM categories";
        $all_categories = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories)) : ?>
            <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category__button"><?= $category['title'] ?></a>
        <?php endwhile ?>
    </div>
</section>
<!--====================== sfarsitul butoanelor din categorii ====================-->




<?php include 'partials/footer.php' ?>