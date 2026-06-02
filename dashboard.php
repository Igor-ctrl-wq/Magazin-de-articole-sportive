<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contul meu - SportZone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>

<?php include 'header.php'; ?>

<section class="dashboard-section">
    <div class="dashboard-inner">

        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="user-card">
                <div class="user-avatar">
                    <?= strtoupper(substr($user['nume'], 0, 1)) ?>
                </div>
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($user['nume']) ?></span>
                    <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="sidebar-link active">
                    <i class="ti ti-layout-dashboard"></i> Prezentare generală
                </a>
                <a href="#" class="sidebar-link">
                    <i class="ti ti-shopping-bag"></i> Comenzile mele
                </a>
                <a href="#" class="sidebar-link">
                    <i class="ti ti-heart"></i> Favorite
                </a>
                <a href="#" class="sidebar-link">
                    <i class="ti ti-user"></i> Datele mele
                </a>
                <a href="#" class="sidebar-link">
                    <i class="ti ti-map-pin"></i> Adrese livrare
                </a>
                <a href="logout.php" class="sidebar-link sidebar-logout">
                    <i class="ti ti-logout"></i> Deconectare
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="dashboard-main">

            <div class="dashboard-welcome">
                <div>
                    <h1 class="dashboard-title">Bună, <?= htmlspecialchars(explode(' ', $user['nume'])[0]) ?>! 👋</h1>
                    <p class="dashboard-subtitle">Bine ai revenit în contul tău SportZone</p>
                </div>
                <a href="logout.php" class="btn-logout-top">
                    <i class="ti ti-logout"></i> Deconectare
                </a>
            </div>

            <!-- Stats -->
            <div class="dash-stats">
                <div class="dash-stat-card">
                    <div class="dash-stat-icon orange">
                        <i class="ti ti-shopping-bag"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number">0</span>
                        <span class="dash-stat-label">Comenzi plasate</span>
                    </div>
                </div>
                <div class="dash-stat-card">
                    <div class="dash-stat-icon navy">
                        <i class="ti ti-heart"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number">0</span>
                        <span class="dash-stat-label">Produse favorite</span>
                    </div>
                </div>
                <div class="dash-stat-card">
                    <div class="dash-stat-icon green">
                        <i class="ti ti-coin"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number">0 lei</span>
                        <span class="dash-stat-label">Total cheltuit</span>
                    </div>
                </div>
            </div>

            <!-- Comenzi recente -->
            <div class="dash-block">
                <div class="dash-block-header">
                    <h2 class="dash-block-title"><i class="ti ti-shopping-bag"></i> Comenzi recente</h2>
                </div>
                <div class="dash-empty">
                    <i class="ti ti-shopping-cart-off"></i>
                    <p>Nu ai nicio comandă încă.</p>
                    <a href="index.php" class="btn-hero-primary" style="margin-top:8px; font-size:14px; padding: 10px 22px;">
                        <i class="ti ti-shopping-bag"></i> Cumpără acum
                    </a>
                </div>
            </div>

            <!-- Info cont -->
            <div class="dash-block">
                <div class="dash-block-header">
                    <h2 class="dash-block-title"><i class="ti ti-user"></i> Informații cont</h2>
                </div>
                <div class="dash-info-grid">
                    <div class="dash-info-item">
                        <span class="dash-info-label">Nume complet</span>
                        <span class="dash-info-value"><?= htmlspecialchars($user['nume']) ?></span>
                    </div>
                    <div class="dash-info-item">
                        <span class="dash-info-label">Adresă email</span>
                        <span class="dash-info-value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="dash-info-item">
                        <span class="dash-info-label">Status cont</span>
                        <span class="dash-info-value"><span class="badge-active">Activ</span></span>
                    </div>
                </div>
            </div>

        </main>
    </div>
</section>

<?php include 'footer.php'; ?>

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