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

<!-- HERO -->
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
                <a href="#produse" class="btn-hero-primary">
                    <i class="ti ti-shopping-bag"></i>
                    Cumpără acum
                </a>
                <a href="#produse" class="btn-hero-secondary">
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
                <div class="hero-float-card card-1">
                    <div class="hero-float-icon"><i class="ti ti-truck-delivery"></i></div>
                    <div>
                        Livrare gratuită
                        <span class="hero-float-sub">La comenzi peste 300 lei</span>
                    </div>
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

<!-- PRODUSE -->
<section class="products-section" id="produse">
    <div class="products-inner">

        <div class="section-header">
            <div>
                <h2 class="section-title">Produse recomandate</h2>
                <p class="section-subtitle">Cele mai populare articole sportive din colecția noastră</p>
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="toate">Toate</button>
                <button class="filter-tab" data-filter="Încălțăminte">Încălțăminte</button>
                <button class="filter-tab" data-filter="Îmbrăcăminte">Îmbrăcăminte</button>
                <button class="filter-tab" data-filter="Echipamente">Echipamente</button>
                <button class="filter-tab" data-filter="Accesorii">Accesorii</button>
            </div>
        </div>

        <?php
        $produse = [];
        $fisier = 'data/items.json';
        if (file_exists($fisier)) {
            $produse = json_decode(file_get_contents($fisier), true) ?? [];
        }
        ?>

        <?php if (empty($produse)): ?>
            <div class="products-empty">
                <i class="ti ti-mood-empty"></i>
                <p>Nu există produse disponibile momentan.</p>
            </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($produse as $produs): ?>
            <div class="product-card" data-categorie="<?= htmlspecialchars($produs['categorie']) ?>">
                <div class="product-img">
                    <?php if (file_exists($produs['imagine'])): ?>
                        <img src="<?= htmlspecialchars($produs['imagine']) ?>" alt="<?= htmlspecialchars($produs['nume']) ?>">
                    <?php else: ?>
                        <div class="product-img-placeholder">
                            <i class="ti ti-photo"></i>
                        </div>
                    <?php endif; ?>
                    <span class="product-category"><?= htmlspecialchars($produs['categorie']) ?></span>
                    <?php if ($produs['stoc'] <= 10): ?>
                        <span class="product-stock-low">Stoc limitat</span>
                    <?php endif; ?>
                </div>
                <div class="product-body">
                    <h3 class="product-name"><?= htmlspecialchars($produs['nume']) ?></h3>
                    <p class="product-desc"><?= htmlspecialchars($produs['descriere']) ?></p>
                    <div class="product-rating">
                        <?php
                        $rating = $produs['rating'];
                        for ($i = 1; $i <= 5; $i++):
                        ?>
                            <i class="ti <?= $i <= round($rating) ? 'ti-star-filled' : 'ti-star' ?>" style="color: <?= $i <= round($rating) ? '#e8511a' : '#ddd' ?>"></i>
                        <?php endfor; ?>
                        <span><?= $rating ?></span>
                    </div>
                    <div class="product-footer">
                        <span class="product-price"><?= number_format($produs['pret'], 0, ',', '.') ?> lei</span>
                        <?php if (isset($_SESSION['user'])): ?>
                            <button class="btn-add-cart" onclick="adaugaInCos(<?= $produs['id'] ?>)">
                                <i class="ti ti-shopping-cart-plus"></i>
                                Adaugă
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="btn-add-cart">
                                <i class="ti ti-shopping-cart-plus"></i>
                                Adaugă
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script>
    // Hamburger
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobileNav');
    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            mobileNav.classList.toggle('open');
        });
    }

    // Filtrare produse
    const tabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.product-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            cards.forEach(card => {
                if (filter === 'toate' || card.dataset.categorie === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Adaugă în coș
    function adaugaInCos(id) {
        let cos = JSON.parse(localStorage.getItem('cos') || '[]');
        if (!cos.includes(id)) {
            cos.push(id);
            localStorage.setItem('cos', JSON.stringify(cos));
        }
        const badge = document.querySelector('.cart-badge');
        if (badge) badge.textContent = cos.length;

        // Feedback vizual
        alert('Produs adăugat în coș!');
    }
</script>
</body>
</html>