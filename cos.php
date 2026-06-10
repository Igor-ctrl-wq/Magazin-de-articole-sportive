<?php
session_start();

$produse = [];
$fisierProduse = 'data/items.json';

if (file_exists($fisierProduse)) {
    $produse = json_decode(file_get_contents($fisierProduse), true) ?? [];
}

$produseById = [];
foreach ($produse as $p) {
    $produseById[(int)$p['id']] = $p;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Trebuie să fii autentificat.']);
        exit;
    }

    $cos = json_decode($_POST['cos'] ?? '[]', true);

    if (empty($cos)) {
        echo json_encode(['success' => false, 'message' => 'Coșul este gol.']);
        exit;
    }

    $items = [];
    $total = 0;

    foreach ($cos as $item) {
        if (is_array($item)) {
            $id = (int)($item['id'] ?? 0);
            $qty = max(1, (int)($item['qty'] ?? 1));
        } else {
            $id = (int)$item;
            $qty = 1;
        }

        if (!isset($produseById[$id])) continue;

        $produs = $produseById[$id];
        $subtotal = (float)$produs['pret'] * $qty;
        $total += $subtotal;

        $items[] = [
            'id' => $id,
            'nume' => $produs['nume'],
            'categorie' => $produs['categorie'],
            'pret' => $produs['pret'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
    }

    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'Produsele din coș nu sunt valide.']);
        exit;
    }

    $fisierComenzi = 'data/orders.json';

    if (!file_exists($fisierComenzi)) {
        file_put_contents($fisierComenzi, '[]');
    }

    $comenzi = json_decode(file_get_contents($fisierComenzi), true);
    if (!is_array($comenzi)) {
        $comenzi = [];
    }

    $comenzi[] = [
        'id' => time(),
        'user_id' => $_SESSION['user']['id'],
        'user_nume' => $_SESSION['user']['nume'],
        'user_email' => $_SESSION['user']['email'],
        'items' => $items,
        'total' => $total,
        'status' => 'Nouă',
        'data' => date('Y-m-d H:i:s')
    ];

    $salvat = file_put_contents($fisierComenzi, json_encode($comenzi, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    if ($salvat === false) {
        echo json_encode(['success' => false, 'message' => 'Nu s-a putut salva comanda în orders.json.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Comanda a fost plasată cu succes!']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Coș - SportZone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="cos-section">
    <div class="cos-inner">
        <div class="cos-header">
            <h1 class="cos-title">
                <i class="ti ti-shopping-cart"></i>
                Coșul meu
            </h1>

            <a href="index.php#produse" class="btn-back">
                Continuă cumpărăturile
            </a>
        </div>

        <div class="cos-layout">
            <div class="cos-items" id="cosItems">
                <div class="cos-empty" id="cosEmpty" style="display:none">
                    <i class="ti ti-shopping-cart-off"></i>
                    <p>Coșul tău este gol.</p>
                </div>
            </div>

            <aside class="cos-summary" id="cosSummary">
                <h2 class="summary-title">Sumar comandă</h2>

                <div class="summary-row">
                    <span>Total</span>
                    <strong id="total">0 lei</strong>
                </div>

                <?php if (isset($_SESSION['user'])): ?>
                    <button type="button" class="btn-comanda" id="btnComanda">
                        Plasează comanda
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-comanda">
                        Autentifică-te pentru a comanda
                    </a>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

<script>
const PRODUSE = <?= json_encode(array_values($produseById), JSON_UNESCAPED_UNICODE) ?>;
const PRODUSE_BY_ID = {};
PRODUSE.forEach(p => PRODUSE_BY_ID[Number(p.id)] = p);

function normalizeCos() {
    let cos = JSON.parse(localStorage.getItem('cos') || '[]');

    cos = cos.map(item => {
        if (typeof item === 'number') {
            return { id: item, qty: 1 };
        }

        if (typeof item === 'string') {
            return { id: Number(item), qty: 1 };
        }

        return {
            id: Number(item.id),
            qty: Number(item.qty || 1)
        };
    });

    cos = cos.filter(item => item.id && PRODUSE_BY_ID[item.id]);

    localStorage.setItem('cos', JSON.stringify(cos));
    return cos;
}

function getCos() {
    return normalizeCos();
}

function saveCos(cos) {
    localStorage.setItem('cos', JSON.stringify(cos));
    renderCos();

    if (typeof updateCartBadge === 'function') {
        updateCartBadge();
    }
}

function formatPret(pret) {
    return Number(pret).toLocaleString('ro-RO') + ' lei';
}

function stergeItem(id) {
    let cos = getCos().filter(item => Number(item.id) !== Number(id));
    saveCos(cos);
}

function schimbaQty(id, delta) {
    let cos = getCos();
    let item = cos.find(p => Number(p.id) === Number(id));

    if (!item) return;

    item.qty = Number(item.qty || 1) + Number(delta);

    if (item.qty <= 0) {
        cos = cos.filter(p => Number(p.id) !== Number(id));
    }

    saveCos(cos);
}

function renderCos() {
    const cos = getCos();
    const container = document.getElementById('cosItems');
    const empty = document.getElementById('cosEmpty');
    const summary = document.getElementById('cosSummary');

    container.querySelectorAll('.cos-card').forEach(el => el.remove());

    if (cos.length === 0) {
        empty.style.display = 'flex';
        summary.style.display = 'none';
        document.getElementById('total').textContent = '0 lei';
        return;
    }

    empty.style.display = 'none';
    summary.style.display = 'block';

    let total = 0;

    cos.forEach(item => {
        const produs = PRODUSE_BY_ID[Number(item.id)];
        if (!produs) return;

        const qty = Number(item.qty || 1);
        const subtotal = Number(produs.pret) * qty;
        total += subtotal;

        const card = document.createElement('div');
        card.className = 'cos-card';

        card.innerHTML = `
            <div class="cos-card-img">
                <img src="${produs.imagine}" alt="${produs.nume}">
            </div>

            <div class="cos-card-body">
                <div class="cos-card-top">
                    <div>
                        <span class="cos-card-cat">${produs.categorie}</span>
                        <h3 class="cos-card-name">${produs.nume}</h3>
                    </div>

                    <button type="button" class="cos-delete" onclick="stergeItem(${produs.id})">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>

                <div class="cos-card-bottom">
                    <div class="qty-box">
                        <button type="button" onclick="schimbaQty(${produs.id}, -1)">-</button>
                        <span>${qty}</span>
                        <button type="button" onclick="schimbaQty(${produs.id}, 1)">+</button>
                    </div>

                    <div class="cos-price">
                        <span>${formatPret(produs.pret)}</span>
                        <strong>${formatPret(subtotal)}</strong>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(card);
    });

    document.getElementById('total').textContent = formatPret(total);
}

const btnComanda = document.getElementById('btnComanda');

if (btnComanda) {
    btnComanda.addEventListener('click', () => {
        const cos = getCos();

        if (cos.length === 0) {
            alert('Coșul este gol.');
            return;
        }

        const formData = new FormData();
        formData.append('place_order', '1');
        formData.append('cos', JSON.stringify(cos));

        fetch('cos.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(text => {
            let data;

            try {
                data = JSON.parse(text);
            } catch (e) {
                alert('Eroare PHP: ' + text);
                return;
            }

            alert(data.message);

            if (data.success) {
                localStorage.removeItem('cos');
                window.location.href = 'dashboard.php';
            }
        })
        .catch(err => {
            alert('Eroare la comandă: ' + err);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    renderCos();

    if (typeof updateCartBadge === 'function') {
        updateCartBadge();
    }
});
</script>

</body>
</html>