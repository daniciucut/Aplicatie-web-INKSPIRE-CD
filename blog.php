<?php
include 'partials/header.php';



//afiseaza toate postarile din tabela (post) 

$query = "SELECT * FROM posts ORDER BY date_time DESC";  //ordonam dupa data de postare
$posts = mysqli_query($connection, $query);
?>




<?php
 $lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta pentru sesiunea din Index.php

// determina campurile din baza de daet in functie de limba curenta
 $title_field = $lang === 'en' ? 'title_en' : 'title';
 $body_field = $lang === 'en' ? 'body_en' : 'body';

 //pentru descrierea categoriilor
 $description_field = $lang === 'en' ? 'description_en' : 'description'; 


?>



<section class="search__bar">
    <form class="container search__bar-container" action="<?= ROOT_URL ?>search.php" method="GET">
        <div>
            <i class="uil uil-search"></i>
            <input type="search" name="search" placeholder="<?= t('search') ?>">
        </div>
        <button type="submit" name="submit" class="btn"><?= t('start') ?></button>
    </form>
</section>
<!--====================== Sfarsitul Cautarii ====================-->







<section class="posts <?= $featured ? '' : 'section__extra-margin' ?>">
    <div class="container posts__container">
        <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
            <article class="post">
                <div class="post__thumbnail">
                    <img src="./images/<?= $post['thumbnail'] ?>">
                </div>
                <div class="post__info">
                    <?php
                   
                    // afiseaza categoria postarii din tabela categorii folosind category_id

                    $category_id = $post['category_id'];
                    $category_query = "SELECT * FROM categories WHERE id=$category_id";
                    $category_result = mysqli_query($connection, $category_query);
                    $category = mysqli_fetch_assoc($category_result);
                    ?>
                    <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category__button"><?= $category[$title_field] ?></a>
                    <h3 class="post__title">
                        <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= $post[$title_field] ?></a>
                    </h3>
                    <p class="post__body">
                        <?= substr($post[$body_field], 0, 150) ?>...
                    </p>
                    <div class="post__author">
                        <?php

                        //afiseaza autorul din tabela (users) folosind author_id

                        $author_id = $post['author_id'];
                        $author_query = "SELECT * FROM users WHERE id=$author_id";
                        $author_result = mysqli_query($connection, $author_query);
                        $author = mysqli_fetch_assoc($author_result);

                        ?>
                        <div class="post__author-avatar">
                            <img src="./images/<?= $author['avatar'] ?>">
                        </div>
                        <div class="post__author-info">
                            <h5><?= t('by') ?> <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
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
<!--====================== sfarsitul postariilor ====================-->




<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php
        $all_categories_query = "SELECT * FROM categories";
        $all_categories = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories)) : ?>
            <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category__button"><?= $category[$title_field] ?></a>
        <?php endwhile ?>
    </div>
</section>
<!--====================== Sfarsitul butoanelor de categorii ====================-->




<?php
include 'partials/footer.php';

?>