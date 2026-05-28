<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportZone - Echipament de performanță</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php session_start(); ?>
<?php include 'header.php'; ?>

<section class="hero">
    <div class="hero-inner">

        <div class="hero-content">
            <h1 class="hero-title">
                Performanță la<br>
                <span>orice nivel</span>
            </h1>
            <p class="hero-desc">
                Echipamente sportive de calitate pentru alergare, fitness, fotbal și multe altele.
                Livrare rapidă în toată Moldova.
            </p>
            <div class="hero-actions">
                <a href="#" class="btn-hero-primary">
                    <i class="ti ti-shopping-bag"></i>
                    Cumpără acum
                </a>
                <a href="#" class="btn-hero-secondary">
                    <i class="ti ti-category"></i>
                    Vezi categorii
                </a>
            </div>
            <div class="hero-stats">
                <div>
                    <span class="hero-stat-number">500+</span>
                    <span class="hero-stat-label">Produse</span>
                </div>
                <div>
                    <span class="hero-stat-number">12k+</span>
                    <span class="hero-stat-label">Clienți mulțumiți</span>
                </div>
                <div>
                    <span class="hero-stat-number">4.9★</span>
                    <span class="hero-stat-label">Rating mediu</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-img-wrap">
            <div class="hero-img-main">
    <img src="images/hero.jpg" alt="SportZone magazin">
</div>
                </div>
                <div class="hero-float-card card-1">
                    <div class="hero-float-icon"><i class="ti ti-truck-delivery"></i></div>
                </div>
                <div class="hero-float-card card-2">
                    <div class="hero-float-icon"><i class="ti ti-rosette-discount"></i></div>
                    <div>
                        -30% reducere
                        <span class="hero-float-sub">La prima comandă</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<footer class="site-footer">
    <div class="footer-inner">

        <div class="footer-brand">
            <a href="index.php" class="logo">
                <div class="logo-icon">
                    <svg width="26" height="26" viewBox="0 0 32 32" fill="none">
                        <circle cx="16" cy="16" r="13" stroke="white" stroke-width="2"/>
                        <path d="M8 16 Q12 8 16 16 Q20 24 24 16" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        <circle cx="16" cy="16" r="2.5" fill="white"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <span class="logo-name">SportZone</span>
                    <span class="logo-tagline">Echipament de performanță</span>
                </div>
            </a>
            <p class="footer-desc">
                Magazinul tău online de articole sportive. Produse de calitate pentru sportivi amatori și profesioniști.
            </p>
            <div class="footer-social">
                <a href="#" class="social-btn" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                <a href="#" class="social-btn" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                <a href="#" class="social-btn" aria-label="TikTok"><i class="ti ti-brand-tiktok"></i></a>
                <a href="#" class="social-btn" aria-label="YouTube"><i class="ti ti-brand-youtube"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Produse</h4>
            <ul class="footer-links">
                <li><a href="#"><i class="ti ti-chevron-right"></i> Încălțăminte</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Îmbrăcăminte</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Echipamente</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Accesorii</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Oferte speciale</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Cont</h4>
            <ul class="footer-links">
                <li><a href="login.php"><i class="ti ti-chevron-right"></i> Autentificare</a></li>
                <li><a href="register.php"><i class="ti ti-chevron-right"></i> Înregistrare</a></li>
                <li><a href="dashboard.php"><i class="ti ti-chevron-right"></i> Contul meu</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Comenzile mele</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Informații</h4>
            <ul class="footer-links">
                <li><a href="contact.php"><i class="ti ti-chevron-right"></i> Contact</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Despre noi</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> Politica de retur</a></li>
                <li><a href="#"><i class="ti ti-chevron-right"></i> GDPR</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <span>© 2025 SportZone. Toate drepturile rezervate.</span>
        <span>
            <a href="#">Termeni și condiții</a> &nbsp;·&nbsp;
            <a href="#">Politica de confidențialitate</a>
        </span>
    </div>
</footer>

<script src="js/script.js"></script>
<script>
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobileNav');
    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });
    }
</script>
</body>
</html>