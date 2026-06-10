<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$searchValue = htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<header class="site-header">
    <div class="header-main">
        <a href="index.php" class="logo">
            <div class="logo-icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <circle cx="16" cy="16" r="14" stroke="white" stroke-width="2"/>
                    <path d="M8 16 Q12 8 16 16 Q20 24 24 16" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                    <circle cx="16" cy="16" r="2.5" fill="white"/>
                </svg>
            </div>

            <div class="logo-text">
                <span class="logo-name">SportZone</span>
            </div>
        </a>

        <nav class="main-nav">
            <a href="index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">Acasă</a>
            <a href="index.php#produse" class="nav-link">Produse</a>
            <a href="index.php#oferte" class="nav-link">Oferte</a>
            <a href="contact.php" class="nav-link <?= $currentPage === 'contact.php' ? 'active' : '' ?>">Contact</a>
        </nav>

        <div class="header-actions">
            <form class="search-bar" action="index.php#produse" method="get">
                <input type="text" id="productSearch" name="q" value="<?= $searchValue ?>" placeholder="Caută produse...">
                <button class="search-btn" type="submit">
                    <i class="ti ti-search"></i>
                </button>
            </form>

            <button type="button" class="icon-btn theme-toggle" id="themeToggle">
                <i class="ti ti-moon"></i>
            </button>

            <div class="lang-switch">
                <button type="button" class="lang-btn" data-lang="ro">RO</button>
                <button type="button" class="lang-btn" data-lang="en">EN</button>
                <button type="button" class="lang-btn" data-lang="ru">RU</button>
            </div>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="dashboard.php" class="icon-btn" title="Contul meu">
                    <i class="ti ti-user-circle"></i>
                </a>
                <a href="logout.php" class="icon-btn" title="Deconectare">
                    <i class="ti ti-logout"></i>
                </a>
            <?php else: ?>
                <a href="login.php" class="btn-outline-header">Autentificare</a>
                <a href="register.php" class="btn-primary-header">Înregistrare</a>
            <?php endif; ?>

            <a href="cos.php" class="icon-btn cart-btn" title="Coș">
                <i class="ti ti-shopping-cart"></i>
                <span class="cart-badge">0</span>
            </a>
        </div>

        <button class="hamburger" id="hamburger" aria-label="Meniu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <nav class="mobile-nav" id="mobileNav">
        <a href="index.php" class="mobile-nav-link">Acasă</a>
        <a href="index.php#produse" class="mobile-nav-link">Produse</a>
        <a href="index.php#oferte" class="mobile-nav-link">Oferte</a>
        <a href="contact.php" class="mobile-nav-link">Contact</a>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="dashboard.php" class="mobile-nav-link">Contul meu</a>
            <a href="logout.php" class="mobile-nav-link">Logout</a>
        <?php else: ?>
            <a href="login.php" class="mobile-nav-link">Autentificare</a>
            <a href="register.php" class="mobile-nav-link">Înregistrare</a>
        <?php endif; ?>
    </nav>
</header>