<!-- about.php -->
<?php include 'partials/header.php'; ?>

<section class="about-section">
  <div class="container about-container">
    <h1><?= t('about_inkspire') ?></h1>
    <p class="intro">
      <?= t('about_intro') ?>
      
      
     </p>

    <div class="about-content">
      <div class="about-text">

      <h2><?= t('our_story') ?></h2>

        <p><?= t('about_description') ?></p>

        <h2><?= t('who_am_i') ?></h2>
        <p><?= t('who_am_i_description') ?></p>

        <h2><?= t('what_you_will_find_here') ?></h2>
        <ul>
  <li><?= t('tech_trends_reviews_projects') ?></li>
  <li><?= t('art_perspectives') ?></li>
  <li><?= t('travel_guides_exclusive_tips') ?></li>
  <li><?= t('personal_dev_resources') ?></li>
  <li><?= t('business_and_leadership_insights') ?></li>
  <li><?= t('digital_productivity_tutorials') ?></li>
        </ul>


        <div class="cta-section">
        <h3><?= t('stay_updated_news') ?></h3>
        <a href="#" id="footer-subscribe-link" class="btn"><?= t('subscribe') ?></a>
        </div>
      </div>

      <div class="about-image">
        <img src="images/about.jpg" alt="Despre Inkspire Blog">
      </div>
    </div>
  </div>
</section>

<!--Sectiunea de Contact-->

<section class="contact-section">
  <div class="container">
    <h2><?= t('contact_us') ?></h2>
    <div class="contact-grid">
      <!-- Card Email -->
      <div class="contact-card">
        <h3>✉️ <?= t('email') ?></h3>
        <p class="contact-text">inkspire.blog@gmail.com</p>
      </div>
      <!-- Card Telefon -->
      <div class="contact-card">
        <h3>📞 <?= t('phone') ?></h3>
        <p class="contact-text">+0040 769 551 745</p>
      </div>
      <!-- Card Mesagerie -->
      <div class="contact-card">
        <h3>💬 <?= t('messaging') ?></h3>
        <p class="contact-text">+0040 769 551 745</p>
      </div>
      <!-- Card Adresă -->
      <div class="contact-card">
        <h3>📍 <?= t('address') ?></h3>
        <p class="contact-text">
          Aleea Studenților <br>
          Timisoara, Romania
        </p>
      </div>
      <!-- Harta -->
      <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11136.393037703981!2d21.23201637802881!3d45.74917603727832!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47455d88f36349cf%3A0x273328993a5715b9!2sComplexul%20Studen%C8%9Besc%2C%20Timi%C8%99oara!5e0!3m2!1sro!2sro!4v1746193697210!5m2!1sro!2sro" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</section>





<?php include 'partials/footer.php'; ?>
