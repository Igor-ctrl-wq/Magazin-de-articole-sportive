<?php
session_start();

$eroare = "";
$succes = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mesaj = trim($_POST['mesaj'] ?? '');

    if ($nume === "" || $email === "" || $mesaj === "") {
        $eroare = "Completează toate câmpurile.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $eroare = "Email invalid.";
    } elseif (strlen($mesaj) < 5) {
        $eroare = "Mesajul trebuie să aibă cel puțin 5 caractere.";
    } else {
        $fisier = 'data/messages.json';
        $mesaje = [];

        if (file_exists($fisier)) {
            $mesaje = json_decode(file_get_contents($fisier), true) ?? [];
        }

        $mesaje[] = [
            'id' => time(),
            'nume' => $nume,
            'email' => $email,
            'mesaj' => $mesaj,
            'data' => date('Y-m-d H:i:s')
        ];

        file_put_contents($fisier, json_encode($mesaje, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $succes = "Mesajul a fost trimis cu succes!";
        $_POST = [];
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - SportZone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="auth-section">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="ti ti-mail"></i>
            </div>
            <h1 class="auth-title" data-i18n="contact.title">Contact</h1>
            <p class="auth-subtitle" data-i18n="contact.subtitle">
                Trimite-ne un mesaj și revenim cât mai rapid.
            </p>
        </div>

        <?php if ($eroare): ?>
            <div class="alert alert-error">
                <i class="ti ti-alert-circle"></i>
                <?= htmlspecialchars($eroare) ?>
            </div>
        <?php endif; ?>

        <?php if ($succes): ?>
            <div class="alert alert-success">
                <i class="ti ti-circle-check"></i>
                <?= htmlspecialchars($succes) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="nume" data-i18n="contact.name">Nume</label>
                <div class="input-wrap">
                    <i class="ti ti-user"></i>
                    <input type="text" id="nume" name="nume" required
                           value="<?= htmlspecialchars($_POST['nume'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email" data-i18n="contact.email">Email</label>
                <div class="input-wrap">
                    <i class="ti ti-mail"></i>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="mesaj" data-i18n="contact.message">Mesaj</label>
                <textarea class="contact-textarea" id="mesaj" name="mesaj" required><?= htmlspecialchars($_POST['mesaj'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-auth">
                <i class="ti ti-send"></i>
                <span data-i18n="contact.send">Trimite mesaj</span>
            </button>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>