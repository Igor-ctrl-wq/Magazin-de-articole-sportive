<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coș de cumpărături - SportZone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php
session_start();

// Citim produsele din JSON
$produse = [];
$fisier = 'data/items.json';
if (file_exists($fisier)) {
    $produse = json_decode(file_get_contents($fisier), true) ?? [];
}

// Indexam produsele dupa ID pentru acces rapid
$produseById = [];
foreach ($produse as $p) {
    $produseById[$p['id']] = $p;
}
?>

<?php include 'header.php'; ?>

<section class="cos-section">
    <div class="cos-inner">

        <div class="cos-header">
            <h1 class="cos-title"><i class="ti ti-shopping-cart"></i> Coșul meu</h1>
            <a href="index.php#produse" class="btn-back">
                <i class="ti ti-arrow-left"></i> Continuă cumpărăturile
            </a>
        </div>

        <div class="cos-layout" id="cosLayout">

            <div class="cos-items" id="cosItems">
                <div class="cos-empty" id="cosEmpty" style="display:none">
                    <i class="ti ti-shopping-cart-off"></i>
                    <p>Coșul tău este gol.</p>
                    <a href="index.php#produse" class="btn-hero-primary" style="margin-top:12px;font-size:14px;padding:11px 24px;">
                        <i class="ti ti-shopping-bag"></i> Vezi produse
                    </a>
                </div>
            </div>

            <aside class="cos-summary" id="cosSummary">
                <h2 class="summary-title">Sumar comandă</h2>

                <div class="summary-rows">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal">0 lei</span>
                    </div>
                    <div class="summary-row">
                        <span>Livrare</span>
                        <span id="livrare">Gratuit</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="total">0 lei</span>
                    </div>
                </div>

                <?php if (isset($_SESSION['user'])): ?>
                    <button class="btn-comanda" id="btnComanda">
                        <i class="ti ti-credit-card"></i>
                        Plasează comanda
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-comanda">
                        <i class="ti ti-login"></i>
                        Autentifică-te pentru a comanda
                    </a>
                <?php endif; ?>

                <div class="summary-garantii">
                    <div class="garantie-item">
                        <i class="ti ti-shield-check"></i>
                        <span>Plată securizată</span>
                    </div>
                    <div class="garantie-item">
                        <i class="ti ti-truck-delivery"></i>
                        <span>Livrare gratuită peste 300 lei</span>
                    </div>
                    <div class="garantie-item">
                        <i class="ti ti-refresh"></i>
                        <span>Retur gratuit 30 zile</span>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

<!-- Date produse din PHP pentru JS -->
<script>
const PRODUSE = <?= json_encode(array_values($produseById)) ?>;
const PRODUSE_BY_ID = {};
PRODUSE.forEach(p => PRODUSE_BY_ID[p.id] = p);

function getCos() {
    return JSON.parse(localStorage.getItem('cos') || '[]');
}

function saveCos(cos) {
    localStorage.setItem('cos', JSON.stringify(cos));
    updateBadge();
}

function updateBadge() {
    const badge = document.querySelector('.cart-badge');
    if (badge) badge.textContent = getCos().length;
}

function formatPret(pret) {
    return pret.toLocaleString('ro-RO') + ' lei';
}

function stergeItem(id) {
    let cos = getCos().filter(i => i.id !== id);
    saveCos(cos);
    renderCos();
}

function schimbaQty(id, delta) {
    let cos = getCos();
    const item = cos.find(i => i.id === id);
    if (!item) return;
    item.qty = (item.qty || 1) + delta;
    if (item.qty <= 0) {
        cos = cos.filter(i => i.id !== id);
    }
    saveCos(cos);
    renderCos();
}

function renderCos() {
    const cos = getCos();
    const container = document.getElementById('cosItems');
    const empty = document.getElementById('cosEmpty');
    const summary = document.getElementById('cosSummary');

    // Sterge cardurile vechi (pastreaza empty div)
    container.querySelectorAll('.cos-card').forEach(el => el.remove());

    if (cos.length === 0) {
        empty.style.display = 'flex';
        summary.style.display = 'none';
        return;
    }

    empty.style.display = 'none';
    summary.style.display = 'flex';

    let subtotal = 0;

    cos.forEach(item => {
        const p = PRODUSE_BY_ID[item.id];
        if (!p) return;
        const qty = item.qty || 1;
        const total = p.pret * qty;
        subtotal += total;

        const card = document.createElement('div');
        card.className = 'cos-card';
        card.innerHTML = `
            <div class="cos-card-img">
                ${p.imagine ? `<img src="${p.imagine}" alt="${p.nume}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">` : ''}
                <div class="cos-img-placeholder" style="${p.imagine ? 'display:none' : ''}">
                    <i class="ti ti-photo"></i>
                </div>
            </div>
            <div class="cos-card-body">
                <div class="cos-card-top">
                    <div>
                        <span class="cos-card-cat">${p.categorie}</span>
                        <h3 class="cos-card-name">${p.nume}</h3>
                    </div>
                    <button class="cos-delete" onclick="stergeItem(${p.id})" title="Șterge">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
                <div class="cos-card-bottom">
                    <div class="cos-qty">
                        <button onclick="schimbaQty(${p.id}, -1)"><i class="ti ti-minus"></i></button>
                        <span>${qty}</span>
                        <button onclick="schimbaQty(${p.id}, 1)"><i class="ti ti-plus"></i></button>
                    </div>
                    <span class="cos-card-pret">${formatPret(total)}</span>
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    // Update summary
    const livrare = subtotal >= 300 ? 0 : 25;
    document.getElementById('subtotal').textContent = formatPret(subtotal);
    document.getElementById('livrare').textContent = livrare === 0 ? 'Gratuit' : formatPret(livrare);
    document.getElementById('total').textContent = formatPret(subtotal + livrare);
}

// Plasare comanda
const btnComanda = document.getElementById('btnComanda');
if (btnComanda) {
    btnComanda.addEventListener('click', () => {
        const cos = getCos();
        if (cos.length === 0) return;
        if (confirm('Confirmi plasarea comenzii?')) {
            saveCos([]);
            renderCos();
            alert('Comanda a fost plasată cu succes! Îți mulțumim!');
        }
    });
}

// Init
updateBadge();
renderCos();
</script>

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