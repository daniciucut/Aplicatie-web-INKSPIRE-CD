<?php
include 'partials/header.php';
?>

<section class="contact-page">
    <div class="container">
    <h1><?= t('contact_page_us') ?></h1>
    <p><?= t('contact_form_instructions') ?></p>
        
    <form id="contactForm" class="contact-form">
  <div class="form-group">
    <label for="name"><?= t('label_name') ?></label>
    <input
      type="text"
      id="name"
      name="name"
      placeholder="<?= t('placeholder_name') ?>"
      required
    >
  </div>
  <div class="form-group">
    <label for="email"><?= t('label_email') ?></label>
    <input
      type="email"
      id="email"
      name="email"
      placeholder="<?= t('placeholder_email') ?>"
      required
    >
  </div>
  <div class="form-group">
    <label for="subject"><?= t('label_subject') ?></label>
    <input
      type="text"
      id="subject"
      name="subject"
      placeholder="<?= t('placeholder_subject') ?>"
      required
    >
  </div>
  <div class="form-group">
    <label for="message"><?= t('label_message') ?></label>
    <textarea
      id="message"
      name="message"
      placeholder="<?= t('placeholder_message') ?>"
      rows="5"
      required
    ></textarea>
  </div>
  <button type="submit" class="btn"><?= t('send_message') ?></button>
  </form>


        <div id="form-response" style="margin-top:15px;"></div>
    </div>



    


</section>

<?php include 'partials/footer.php'; ?>

<!-- Contact, pagina de contact -->
<script>
document.querySelector('.contact-page form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('send-contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById('form-response').innerHTML = result;
        this.reset(); // Resetarea formularului după succes
    })
    .catch(error => {
        document.getElementById('form-response').innerHTML = "A apărut o eroare la trimitere.";
        console.error('Error:', error);
    });
});
</script>
