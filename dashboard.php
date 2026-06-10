<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

$fisierComenzi = 'data/orders.json';
$comenzi = [];

if (file_exists($fisierComenzi)) {
    $comenzi = json_decode(file_get_contents($fisierComenzi), true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $orderId = (int)($_POST['order_id'] ?? 0);

    foreach ($comenzi as $index => $comanda) {
        if ((int)$comanda['id'] === $orderId && (int)$comanda['user_id'] === (int)$user['id']) {

            if ($action === 'delete') {
                unset($comenzi[$index]);
                $comenzi = array_values($comenzi);
            }

            if ($action === 'status') {
                $statusNou = trim($_POST['status'] ?? 'Nouă');
                $statusuriPermise = ['Nouă', 'În procesare', 'Livrată', 'Anulată'];

                if (in_array($statusNou, $statusuriPermise, true)) {
                    $comenzi[$index]['status'] = $statusNou;
                }
            }

            file_put_contents($fisierComenzi, json_encode($comenzi, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            header('Location: dashboard.php');
            exit;
        }
    }
}

$comenziUser = array_values(array_filter($comenzi, function ($comanda) use ($user) {
    return (int)($comanda['user_id'] ?? 0) === (int)$user['id'];
}));

usort($comenziUser, function ($a, $b) {
    return (int)$b['id'] <=> (int)$a['id'];
});

$totalCheltuit = 0;
$totalProduse = 0;
$ultimaComanda = '—';

foreach ($comenziUser as $comanda) {
    $totalCheltuit += (float)($comanda['total'] ?? 0);

    foreach (($comanda['items'] ?? []) as $item) {
        $totalProduse += (int)($item['qty'] ?? 1);
    }
}

if (!empty($comenziUser)) {
    $ultimaComanda = $comenziUser[0]['data'] ?? '—';
}
?>

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

<?php include 'header.php'; ?>

<section class="dashboard-section">
    <div class="dashboard-inner">

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
                <a href="dashboard.php" class="sidebar-link active">
                    <i class="ti ti-layout-dashboard"></i>
                    Prezentare generală
                </a>

                <a href="#ordersBlock" class="sidebar-link">
                    <i class="ti ti-shopping-bag"></i>
                    Comenzile mele
                </a>

                <a href="index.php#produse" class="sidebar-link">
                    <i class="ti ti-heart"></i>
                    Produse favorite
                </a>

                <a href="#accountInfo" class="sidebar-link">
                    <i class="ti ti-user-cog"></i>
                    Setări cont
                </a>

                <a href="contact.php" class="sidebar-link">
                    <i class="ti ti-mail"></i>
                    Contact
                </a>

                <a href="logout.php" class="sidebar-link sidebar-logout">
                    <i class="ti ti-logout"></i>
                    Deconectare
                </a>
            </nav>
        </aside>

        <main class="dashboard-main">

            <div class="dashboard-welcome">
                <div>
                    <h1 class="dashboard-title">
                        Bună, <?= htmlspecialchars(explode(' ', $user['nume'])[0]) ?>! 👋
                    </h1>
                    <p class="dashboard-subtitle">
                        Bine ai revenit în contul tău SportZone.
                    </p>
                </div>

                <a href="logout.php" class="btn-logout-top">
                    <i class="ti ti-logout"></i>
                    Deconectare
                </a>
            </div>

            <div class="dash-stats dashboard-stats-four">
                <div class="dash-stat-card">
                    <div class="dash-stat-icon orange">
                        <i class="ti ti-shopping-bag"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number"><?= count($comenziUser) ?></span>
                        <span class="dash-stat-label">Comenzi plasate</span>
                    </div>
                </div>

                <div class="dash-stat-card">
                    <div class="dash-stat-icon navy">
                        <i class="ti ti-package"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number"><?= $totalProduse ?></span>
                        <span class="dash-stat-label">Produse comandate</span>
                    </div>
                </div>

                <div class="dash-stat-card">
                    <div class="dash-stat-icon green">
                        <i class="ti ti-coin"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number"><?= number_format($totalCheltuit, 0, ',', '.') ?> lei</span>
                        <span class="dash-stat-label">Total cheltuit</span>
                    </div>
                </div>

                <div class="dash-stat-card">
                    <div class="dash-stat-icon blue">
                        <i class="ti ti-calendar"></i>
                    </div>
                    <div>
                        <span class="dash-stat-number" style="font-size:18px;">
                            <?= htmlspecialchars($ultimaComanda) ?>
                        </span>
                        <span class="dash-stat-label">Ultima comandă</span>
                    </div>
                </div>
            </div>

            <div class="dash-block" id="ordersBlock">
                <div class="dash-block-header">
                    <h2 class="dash-block-title">
                        <i class="ti ti-shopping-bag"></i>
                        Comenzile mele
                    </h2>
                </div>

                <?php if (empty($comenziUser)): ?>
                    <div class="dash-empty">
                        <i class="ti ti-shopping-cart-off"></i>
                        <p>Nu ai nicio comandă încă.</p>

                        <a href="index.php#produse" class="btn-hero-primary" style="margin-top:8px; font-size:14px; padding: 10px 22px;">
                            <i class="ti ti-shopping-bag"></i>
                            Cumpără acum
                        </a>
                    </div>
                <?php else: ?>
                    <div class="orders-table-wrap">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>ID Comandă</th>
                                    <th>Data</th>
                                    <th>Produse</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($comenziUser as $comanda): ?>
                                    <?php
                                    $nrProduse = 0;
                                    foreach (($comanda['items'] ?? []) as $item) {
                                        $nrProduse += (int)($item['qty'] ?? 1);
                                    }

                                    $status = $comanda['status'] ?? 'Nouă';
                                    $statusClass = 'status-new';

                                    if ($status === 'Livrată') {
                                        $statusClass = 'status-delivered';
                                    } elseif ($status === 'În procesare') {
                                        $statusClass = 'status-processing';
                                    } elseif ($status === 'Anulată') {
                                        $statusClass = 'status-canceled';
                                    }
                                    ?>

                                    <tr>
                                        <td>#<?= htmlspecialchars($comanda['id']) ?></td>
                                        <td><?= htmlspecialchars($comanda['data'] ?? '-') ?></td>
                                        <td><?= $nrProduse ?> <?= $nrProduse === 1 ? 'produs' : 'produse' ?></td>
                                        <td><?= number_format((float)($comanda['total'] ?? 0), 0, ',', '.') ?> lei</td>
                                        <td>
                                            <span class="status-pill <?= $statusClass ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <button type="button" class="btn-icon-table" onclick="toggleOrderDetails('order-<?= (int)$comanda['id'] ?>')" title="Detalii">
                                                    <i class="ti ti-eye"></i>
                                                </button>

                                                <form method="POST" class="inline-form">
                                                    <input type="hidden" name="action" value="status">
                                                    <input type="hidden" name="order_id" value="<?= (int)$comanda['id'] ?>">

                                                    <select name="status" class="status-select">
                                                        <option value="Nouă" <?= $status === 'Nouă' ? 'selected' : '' ?>>Nouă</option>
                                                        <option value="În procesare" <?= $status === 'În procesare' ? 'selected' : '' ?>>În procesare</option>
                                                        <option value="Livrată" <?= $status === 'Livrată' ? 'selected' : '' ?>>Livrată</option>
                                                        <option value="Anulată" <?= $status === 'Anulată' ? 'selected' : '' ?>>Anulată</option>
                                                    </select>

                                                    <button type="submit" class="btn-icon-table" title="Modifică">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                </form>

                                                <form method="POST" class="inline-form" onsubmit="return confirm('Sigur vrei să ștergi comanda?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="order_id" value="<?= (int)$comanda['id'] ?>">

                                                    <button type="submit" class="btn-icon-table btn-delete-table" title="Șterge">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="order-details-row" id="order-<?= (int)$comanda['id'] ?>" style="display:none;">
                                        <td colspan="6">
                                            <div class="order-details-box">
                                                <strong>Produse comandate:</strong>

                                                <?php foreach (($comanda['items'] ?? []) as $item): ?>
                                                    <div class="order-detail-item">
                                                        <span>
                                                            <?= htmlspecialchars($item['nume']) ?>
                                                            x<?= (int)($item['qty'] ?? 1) ?>
                                                        </span>

                                                        <span>
                                                            <?= number_format((float)($item['subtotal'] ?? 0), 0, ',', '.') ?> lei
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="dash-block" id="accountInfo">
                <div class="dash-block-header">
                    <h2 class="dash-block-title">
                        <i class="ti ti-user"></i>
                        Informații cont
                    </h2>
                </div>

                <div class="dash-info-grid">
                    <div class="dash-info-item">
                        <span class="dash-info-label">Nume complet</span>
                        <span class="dash-info-value"><?= htmlspecialchars($user['nume']) ?></span>
                    </div>

                    <div class="dash-info-item">
                        <span class="dash-info-label">Email</span>
                        <span class="dash-info-value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>

                    <div class="dash-info-item">
                        <span class="dash-info-label">Membru din</span>
                        <span class="dash-info-value">Cont activ</span>
                    </div>

                    <div class="dash-info-item">
                        <span class="dash-info-label">ID utilizator</span>
                        <span class="dash-info-value">#<?= htmlspecialchars($user['id']) ?></span>
                    </div>
                </div>
            </div>

        </main>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script>
function toggleOrderDetails(id) {
    const row = document.getElementById(id);

    if (!row) return;

    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>

</body>
</html>