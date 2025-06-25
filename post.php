<?php
include 'partials/header.php';


 //afiseaza postarea din baza de date daca id este setat
 
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($result);
} else {
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}
?>




<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/tk6hvg564535ymi490vxixtxoyey8x5kd959tnk689xohn2w/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea#post_content',  // ID-ul textarea-ului unde va fi editorul
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    height: 400
  });
</script>




<?php
$lang = $_SESSION['lang'] ?? 'ro'; // stabileste limba curenta

// determina campurile din baza de daet in functie de limba curenta
$title_field = $lang === 'en' ? 'title_en' : 'title';
$body_field = $lang === 'en' ? 'body_en' : 'body';
?>





<section class="singlepost">
  <div class="singlepost__wrapper">  <!-- Un singur container pentru amandoua-->
    <div class="container singlepost__container">

     
     <h2><?= ($post[$title_field]) ?></h2>
     

      <div class="post__author">
        <?php
          // Afișează autorul
          $author_id = $post['author_id'];
          $author_query = "SELECT * FROM users WHERE id=$author_id";
          $author_result = mysqli_query($connection, $author_query);
          $author = mysqli_fetch_assoc($author_result);
        ?>
        <div class="post__author-avatar">
          <img src="./images/<?= $author['avatar'] ?>">
        </div>
        <div class="post__author-info">
          
          <h5><?= t('by') ?><?= "{$author['firstname']} {$author['lastname']}" ?></h5>
          <small><?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></small>
        </div>
      </div>
      <div class="singlepost__thumbnail">
        <img src="./images/<?= $post['thumbnail'] ?>">
      </div>
      
     
     <div><?= ($post[$body_field]) ?></div>
    </div>
    
    <aside class="recent-posts-sidebar">

      <h3><?= t('latest_posts') ?></h3>
      <?php
        $recent_query = "SELECT id, thumbnail, {$title_field} AS title_display FROM posts ORDER BY date_time DESC LIMIT 5";
        $recent_result = mysqli_query($connection, $recent_query);
        while ($recent_post = mysqli_fetch_assoc($recent_result)) :
      ?>
        <div class="recent-post">
          <a href="post.php?id=<?= $recent_post['id'] ?>">
            <img src="./images/<?= $recent_post['thumbnail'] ?>" alt="<?= htmlspecialchars($recent_post['title_display']) ?>">
            <span><?= htmlspecialchars_decode($recent_post['title_display']) ?></span> 
          </a>
        </div>
      <?php endwhile; ?>
    </aside>
  </div>
</section>

<!--====================== sfarsitul unei singure postari ====================-->











<!-- Secțiunea pentru formularul de adăugare a comentariilor -->
<section class="comment-form">
    <h3><?= t('add_comment') ?></h3>
    <form action="<?= ROOT_URL ?>add-comment.php" method="POST">
        <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
<input
  type="text"
  name="user_name"
  placeholder="<?= t('placeholder_user_name') ?>"
  required
>
<textarea
  name="comment"
  placeholder="<?= t('placeholder_comment') ?>"
  required
></textarea>
<button type="submit" name ="submit"><?= t('submit_comment') ?></button>

    </form>
</section>

<!-- Secțiunea pentru afișarea comentariilor existente -->
<section class="comment-section">
    <h3><?= t('comments') ?></h3>

    <?php
    // Include fișierul de conexiune sau asigură-te că variabila $conn este disponibilă
    require_once __DIR__ . '/config/database.php';
    
    $post_id = $post['id'];
    $stmt = $connection->prepare("SELECT user_name, comment, created_at FROM comments WHERE post_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='comment'>";
            echo "<p class='comment-author'><strong>" . htmlspecialchars($row['user_name']) . ":</strong></p>";
            echo "<p>" . htmlspecialchars($row['comment']) . "</p>";
            echo "<p class='comment-date'>" . $row['created_at'] . "</p>";
            echo "</div>";
        }
    } else {
        echo '<p>' . t('no_comments_yet') . '</p>';
    }
    ?>
</section>






<?php
include 'partials/footer.php';

?>

