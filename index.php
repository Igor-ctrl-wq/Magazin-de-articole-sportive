<?php
session_start();

$produse = [];
$fisier = 'data/items.json';

if (file_exists($fisier)) {
    $produse = json_decode(file_get_contents($fisier), true) ?? [];
}

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $produse = array_filter($produse, function ($produs) use ($q) {
        $text = strtolower(
            ($produs['nume'] ?? '') . ' ' .
            ($produs['categorie'] ?? '') . ' ' .
            ($produs['descriere'] ?? '')
        );

        return str_contains($text, strtolower($q));
    });
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportZone - Magazin articole sportive</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">

            <h1 class="hero-title" data-i18n="hero.title">
                Cea mai bună<br>
                <span>calitate</span>
            </h1>

            <p class="hero-desc" data-i18n="hero.desc">
                Articole sportive premium pentru alergare, fitness, fotbal și antrenamente.
                Alege produse de calitate pentru performanță la orice nivel.
            </p>

            <div class="hero-actions">
                <a href="#produse" class="btn-hero-primary">
                    <i class="ti ti-shopping-bag"></i>
                    <span data-i18n="hero.buy">Cumpără acum</span>
                </a>

                <a href="#produse" class="btn-hero-secondary">
                    <i class="ti ti-category"></i>
                    <span data-i18n="hero.categories">Vezi categorii</span>
                </a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-img-wrap">
                <div class="hero-img-main">
                    <img src="images/hero.jpg" alt="Articole sportive SportZone">
                </div>

                <div class="hero-float-card card-1">
                    <div class="hero-float-icon">
                        <i class="ti ti-truck-delivery"></i>
                    </div>
                    <div>
                        <span data-i18n="hero.deliveryTitle">Livrare rapidă</span>
                        <span class="hero-float-sub" data-i18n="hero.deliverySub">În toată Moldova</span>
                    </div>
                </div>

                <div class="hero-float-card card-2">
                    <div class="hero-float-icon">
                        <i class="ti ti-rosette-discount"></i>
                    </div>
                    <div>
                        <span data-i18n="hero.offerTitle">Oferte speciale</span>
                        <span class="hero-float-sub" data-i18n="hero.offerSub">Reduceri la produse selectate</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="products-section" id="produse">
    <div class="products-inner">

        <div class="section-header">
            <div>
                <h2 class="section-title" data-i18n="products.title">Produse recomandate</h2>
                <p class="section-subtitle" data-i18n="products.subtitle">
                    Cele mai populare articole sportive din colecția noastră
                </p>
            </div>

            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="toate" data-i18n="products.all">Toate</button>
                <button class="filter-tab" data-filter="Încălțăminte" data-i18n="products.shoes">Încălțăminte</button>
                <button class="filter-tab" data-filter="Îmbrăcăminte" data-i18n="products.clothes">Îmbrăcăminte</button>
                <button class="filter-tab" data-filter="Echipamente" data-i18n="products.equipment">Echipamente</button>
                <button class="filter-tab" data-filter="Accesorii" data-i18n="products.accessories">Accesorii</button>
            </div>
        </div>

        <?php if ($q !== ''): ?>
            <p class="section-subtitle" style="margin-bottom:20px;">
                Rezultate pentru: <strong><?= htmlspecialchars($q) ?></strong>
            </p>
        <?php endif; ?>

        <?php if (empty($produse)): ?>
            <div class="products-empty">
                <i class="ti ti-mood-empty"></i>
                <p data-i18n="products.empty">Nu există produse disponibile momentan.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($produse as $produs): ?>
                    <div class="product-card" data-categorie="<?= htmlspecialchars($produs['categorie']) ?>">
                        <div class="product-img">
                            <?php if (!empty($produs['imagine']) && file_exists($produs['imagine'])): ?>
                                <img src="<?= htmlspecialchars($produs['imagine']) ?>" alt="<?= htmlspecialchars($produs['nume']) ?>">
                            <?php else: ?>
                                <div class="product-img-placeholder">
                                    <i class="ti ti-photo"></i>
                                </div>
                            <?php endif; ?>

                            <span class="product-category">
                                <?= htmlspecialchars($produs['categorie']) ?>
                            </span>

                            <?php if ((int)$produs['stoc'] <= 10): ?>
                                <span class="product-stock-low" data-i18n="products.lowStock">Stoc limitat</span>
                            <?php endif; ?>
                        </div>

                        <div class="product-body">
                            <h3 class="product-name">
                                <?= htmlspecialchars($produs['nume']) ?>
                            </h3>

                            <p class="product-desc">
                                <?= htmlspecialchars($produs['descriere']) ?>
                            </p>

                            <div class="product-rating">
                                <?php
                                $rating = (float)$produs['rating'];
                                for ($i = 1; $i <= 5; $i++):
                                ?>
                                    <i class="ti <?= $i <= round($rating) ? 'ti-star-filled' : 'ti-star' ?>" style="color: <?= $i <= round($rating) ? '#e8511a' : '#ddd' ?>"></i>
                                <?php endfor; ?>

                                <span><?= htmlspecialchars($produs['rating']) ?></span>
                            </div>

                            <div class="product-footer">
                                <span class="product-price">
                                    <?= number_format((float)$produs['pret'], 0, ',', '.') ?> lei
                                </span>

                                <?php if (isset($_SESSION['user'])): ?>
                                    <button class="btn-add-cart" onclick="adaugaInCos(<?= (int)$produs['id'] ?>)">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                        <span data-i18n="products.add">Adaugă</span>
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn-add-cart">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                        <span data-i18n="products.add">Adaugă</span>
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

<section class="products-section" id="oferte">
    <div class="products-inner">
        <div class="section-header">
            <div>
                <h2 class="section-title" data-i18n="offers.title">Oferte speciale</h2>
                <p class="section-subtitle" data-i18n="offers.subtitle">
                    Reduceri și beneficii pentru clienții SportZone.
                </p>
            </div>
        </div>

        <div class="dash-stats">
            <div class="dash-stat-card">
                <div class="dash-stat-icon orange">
                    <i class="ti ti-rosette-discount"></i>
                </div>
                <div>
                    <span class="dash-stat-number">-30%</span>
                    <span class="dash-stat-label" data-i18n="offers.firstOrder">La prima comandă</span>
                </div>
            </div>

            <div class="dash-stat-card">
                <div class="dash-stat-icon navy">
                    <i class="ti ti-truck-delivery"></i>
                </div>
                <div>
                    <span class="dash-stat-number">0 lei</span>
                    <span class="dash-stat-label" data-i18n="offers.delivery">Livrare peste 300 lei</span>
                </div>
            </div>

            <div class="dash-stat-card">
                <div class="dash-stat-icon green">
                    <i class="ti ti-refresh"></i>
                </div>
                <div>
                    <span class="dash-stat-number">30 zile</span>
                    <span class="dash-stat-label" data-i18n="offers.return">Retur gratuit</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.product-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;

            cards.forEach(card => {
                card.style.display =
                    filter === 'toate' || card.dataset.categorie === filter
                        ? 'flex'
                        : 'none';
            });
        });
    });
});
</script>

</body>
</html>