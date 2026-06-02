<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare - SportZone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php
session_start();

// Daca e deja autentificat, redirectam
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$eroare = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email  = trim($_POST['email'] ?? '');
    $parola = trim($_POST['parola'] ?? '');

    if (empty($email) || empty($parola)) {
        $eroare = "Completează toate câmpurile.";
    } else {
        $fisier = 'data/users.json';

        if (!file_exists($fisier)) {
            $eroare = "Nu există niciun cont înregistrat încă.";
        } else {
            $utilizatori = json_decode(file_get_contents($fisier), true) ?? [];
            $gasit = false;

            foreach ($utilizatori as $u) {
                if ($u['email'] === $email && password_verify($parola, $u['parola'])) {
                    // Autentificare reusita
                    $_SESSION['user'] = [
                        'id'    => $u['id'],
                        'nume'  => $u['nume'],
                        'email' => $u['email']
                    ];
                    $gasit = true;
                    header('Location: dashboard.php');
                    exit;
                }
            }

            if (!$gasit) {
                $eroare = "Email sau parolă incorectă.";
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="auth-section">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-icon">
                <i class="ti ti-login"></i>
            </div>
            <h1 class="auth-title">Bine ai revenit</h1>
            <p class="auth-subtitle">Autentifică-te în contul tău SportZone</p>
        </div>

        <?php if ($eroare): ?>
            <div class="alert alert-error">
                <i class="ti ti-alert-circle"></i>
                <?= htmlspecialchars($eroare) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="auth-form">

            <div class="form-group">
                <label for="email">Adresă email</label>
                <div class="input-wrap">
                    <i class="ti ti-mail"></i>
                    <input type="email" id="email" name="email" placeholder="ion@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="parola">Parolă</label>
                <div class="input-wrap">
                    <i class="ti ti-lock"></i>
                    <input type="password" id="parola" name="parola" placeholder="Parola ta" required>
                    <button type="button" class="toggle-pass" onclick="togglePass('parola', this)">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth">
                <i class="ti ti-login"></i>
                Autentifică-te
            </button>

        </form>

        <p class="auth-switch">
            Nu ai cont? <a href="register.php">Înregistrează-te</a>
        </p>

    </div>
</section>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
    } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
    }
}
</script>

</body>
</html>