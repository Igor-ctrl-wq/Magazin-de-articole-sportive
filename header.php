<header class="site-header">
    <div class="header-main">
        <a href="index.php" class="logo">
            <div class="logo-icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="14" stroke="white" stroke-width="2"/>
                    <path d="M8 16 Q12 8 16 16 Q20 24 24 16" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                    <circle cx="16" cy="16" r="2.5" fill="white"/>
                </svg>
            </div>
            <div class="logo-text">
                <span class="logo-name">SportZone</span>
                <span class="logo-tagline">Echipament de performanță</span>
            </div>
        </a>

        <nav class="main-nav">
            <a href="index.php" class="nav-link active">Acasă</a>
            <div class="nav-dropdown">
                <a href="#" class="nav-link">Produse <i class="ti ti-chevron-down"></i></a>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item"><i class="ti ti-shoe"></i> Încălțăminte</a>
                    <a href="#" class="dropdown-item"><i class="ti ti-shirt"></i> Îmbrăcăminte</a>
                    <a href="#" class="dropdown-item"><i class="ti ti-ball-football"></i> Echipamente</a>
                    <a href="#" class="dropdown-item"><i class="ti ti-backpack"></i> Accesorii</a>
                </div>
            </div>
            <a href="#" class="nav-link">Oferte</a>
            <a href="contact.php" class="nav-link">Contact</a>
        </nav>

        <div class="header-actions">
            <div class="search-bar">
                <input type="text" placeholder="Caută produse...">
                <button class="search-btn" aria-label="Caută"><i class="ti ti-search"></i></button>
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

            <a href="cos.php" class="icon-btn cart-btn" title="Coș de cumpărături">
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
        <a href="#" class="mobile-nav-link">Produse</a>
        <a href="#" class="mobile-nav-link">Oferte</a>
        <a href="contact.php" class="mobile-nav-link">Contact</a>
        <div class="mobile-nav-actions">
            <a href="login.php" class="btn-outline-header">Autentificare</a>
            <a href="register.php" class="btn-primary-header">Înregistrare</a>
        </div>
    </nav>
</header>