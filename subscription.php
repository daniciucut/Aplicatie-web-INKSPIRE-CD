<!-- subscription.php -->
<div id="subscription-box" class="fixed-subscription">
    <span id="close-subscription">&times;</span>
    <h4>Abonează-te pentru cele mai noi articole!</h4>
    <form method="POST">
        <input type="email" name="email" placeholder="Introdu adresa ta de email" required>
        <button type="submit" name="subscribe">Abonează-te</button>
    </form>

    <?php if(isset($subscribeMessage)): ?>
        <div class="subscribe-message"><?= $subscribeMessage ?></div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const subscriptionBox = document.getElementById('subscription-box');
    const closeBtn = document.getElementById('close-subscription');
    const subscribeLink = document.querySelector('a[href="#"]');

    // Ascunde dacă a fost închis anterior
    if(localStorage.getItem('subscriptionClosed') === 'true') {
        subscriptionBox.style.display = 'none';
    }

    closeBtn.onclick = () => {
        subscriptionBox.style.display = 'none';
        localStorage.setItem('subscriptionClosed', 'true');
    };

    subscribeLink.onclick = (e) => {
        e.preventDefault(); // previne refresh-ul paginii
        subscriptionBox.style.display = 'block';
        localStorage.setItem('subscriptionClosed', 'false'); // permite reapariția formularului
    };

    <?php if(isset($subscribeMessage) && $subscribeMessage === "Te-ai abonat cu succes! Mulțumim!"): ?>
        setTimeout(() => {
            subscriptionBox.style.display = 'none';
            localStorage.setItem('subscriptionClosed', 'true');
        }, 3000);
    <?php endif; ?>
});
</script>

