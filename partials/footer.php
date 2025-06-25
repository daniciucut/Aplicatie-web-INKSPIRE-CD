<?php include __DIR__ . '/../subscribe-logic.php';?>
<footer>
<!-- Link uri catre retele sociale-->
    <div class="footer__socials">
        <a href="https://www.youtube.com/channel/UCuttPm1ffHrTM_9FCKnjdUw" target="_blank"><i class="uil uil-youtube"></i></a>
        <a href="https://facebook.com" target="_blank"><i class="uil uil-facebook-f"></i></a>
        <a href="https://instagram.com" target="_blank"><i class="uil uil-instagram-alt"></i></a>
        <a href="https://linkedin.com" target="_blank"><i class="uil uil-linkedin"></i></a>
        <a href="https://twitter.com" target="_blank"><i class="uil uil-twitter"></i></a>
    </div>
    <div class="container footer__container">
        <article>
            <h4>Categorii</h4>
            <ul>
                <li><a href="">Artă</a></li>
                <li><a href="">Natură</a></li>
                <li><a href="">Călătorie</a></li>
                <li><a href="">Muzică</a></li>
                <li><a href="">Știință și Tehnologie</a></li>
                <li><a href="">Mâncare</a></li>
            </ul>
        </article>
        <article>
            <h4><?= t('support') ?></h4>
            <ul>
              <li><a href=""><?= t('support_online') ?></a></li>
              <li><a href=""><?= t('support_phone_numbers') ?></a></li>
              <li><a href=""><?= t('support_emails') ?></a></li>
              <li><a href=""><?= t('support_social') ?></a></li>
              <li><a href=""><?= t('support_location') ?></a></li>
            </ul>

        </article>
        <article>
            <h4><?= t('blog') ?></h4>
          <ul>
           <li><a href=""><?= t('blog_safety') ?></a></li>
           <li><a href=""><?= t('blog_repair') ?></a></li>
           <li><a href=""><?= t('blog_recent') ?></a></li>
           <li><a href=""><?= t('blog_popular') ?></a></li>
           <li><a href=""><?= t('blog_categories') ?></a></li>
          </ul>

        </article>
        <article>
            <h4><?= t('useful_links') ?></h4>
          <ul>
           <li><a href="<?= ROOT_URL ?>"><?= t('home') ?></a></li>
           <li><a href="<?= ROOT_URL ?>blog.php"><?= t('blog') ?></a></li>
           <li><a href="<?= ROOT_URL ?>about.php"><?= t('about') ?></a></li>
           <li><a href="#" id="footer-subscribe-link"><?= t('subscribe') ?></a></li>
           <li><a href="<?= ROOT_URL ?>contact.php"><?= t('contact') ?></a></li>
          </ul>

        </article>
    </div>
    <div class="footer__copyright">
        <small>Copyright &copy; 2024-2025  Daniel Cucicea</small>
    </div>
</footer>

<!-- Chat Widget -->
<div id="chatbot-widget" style="position:fixed;bottom:20px;right:20px;z-index:9999;">
  <button id="chatbot-toggle"
          style="background:var(--color-primary);color:var(--color-white);
                 border:none;padding:0.8rem;border-radius:50%;font-size:1.5rem;cursor:pointer;">
    💬
  </button>
  <div id="chatbot-window" style="display:none;flex-direction:column;
                                   width:300px;height:400px;
                                   background:var(--color-gray-900);
                                   color:var(--color-white);
                                   border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.5);
                                   overflow:hidden;margin-top:0.5rem;">
    <div id="chatbot-messages" style="flex:1;padding:0.5rem;overflow-y:auto;font-size:.9rem;"></div>
    <input id="chatbot-input" type="text" placeholder="Scrie un mesaj..."
           style="border:none;padding:.8rem;font-size:.9rem;
                  background:var(--color-gray-700);color:var(--color-white);" />
  </div>
</div>

<script>
  const toggleBtn = document.getElementById('chatbot-toggle');
  const chatWin   = document.getElementById('chatbot-window');
  const chatMsgs  = document.getElementById('chatbot-messages');
  const chatInput = document.getElementById('chatbot-input');

  toggleBtn.addEventListener('click', () => {
    chatWin.style.display = chatWin.style.display === 'flex' ? 'none' : 'flex';
    if (chatWin.style.display === 'flex') chatInput.focus();
  });

  chatInput.addEventListener('keydown', async (e) => {
    if (e.key !== 'Enter') return;
    const msg = chatInput.value.trim();
    if (!msg) return;
    appendMessage('Tu', msg);
    chatInput.value = '';

    try {
      const res = await fetch('http://localhost:5000/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: msg })
      });
      const { reply } = await res.json();
      appendMessage('Inkspire', reply);
    } catch {
      appendMessage('Inkspire', 'Eroare de conexiune.');
    }
  });

  function appendMessage(author, text) {
    const el = document.createElement('div');
    el.style.margin = '0.5rem 0';
    el.innerHTML = `<strong>${author}:</strong> ${text}`;
    chatMsgs.appendChild(el);
    chatMsgs.scrollTop = chatMsgs.scrollHeight;
  }
</script>




<script src="<?= ROOT_URL ?>js/main.js"></script>



<?php include __DIR__ . '/../subscription.php'; ?>





</body>







</html>