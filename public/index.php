<?php
require_once __DIR__ . "/../app/auth.php";
require_once __DIR__ . "/../app/helpers.php";
require_once __DIR__ . "/../partials/header.php";
?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Elevate Your Style, <br><span style="color: var(--primary)">Embrace Your Glow.</span></h1>
            <p>Experience the pinnacle of luxury grooming and hair care. At <?= e(APP_NAME) ?>, we blend artistry with expertise to create your perfect look.</p>
            <div style="display: flex; gap: 1.5rem;">
                <a href="/register.php" class="btn btn-primary">Book Appointment</a>
                <a href="#services" class="btn btn-outline">Explore Services</a>
            </div>
        </div>
    </section>

    <section id="services" style="padding: 8rem 5%;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Signature Services</h2>
            <p style="color: var(--text-muted);">Crafted by experts, tailored to you.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div class="auth-card" style="max-width: 100%; padding: 2rem;">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">Precision Cuts</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Expert styling and cutting techniques to suit your face shape and personality.</p>
            </div>
            <div class="auth-card" style="max-width: 100%; padding: 2rem;">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">Master Color</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">From subtle highlights to bold transformations, our colorists use the finest techniques.</p>
            </div>
            <div class="auth-card" style="max-width: 100%; padding: 2rem;">
                <h3 style="color: var(--primary); margin-bottom: 1rem;">Spa Rituals</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Rejuvenating treatments designed to pamper your scalp and restore your hair's health.</p>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
