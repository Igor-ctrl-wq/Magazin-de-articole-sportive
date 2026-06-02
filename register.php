<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare - SportZone</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php
session_start();

$eroare = "";
$succes = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume     = trim($_POST['nume'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $parola   = trim($_POST['parola'] ?? '');
    $confirma = trim($_POST['confirma'] ?? '');

    // Validari
    if (empty($nume) || empty($email) || empty($parola) || empty($confirma)) {
        $eroare = "Toate câmpurile sunt obligatorii.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $eroare = "Adresa de email nu este validă.";
    } elseif (strlen($parola) < 6) {
        $eroare = "Parola trebuie să aibă cel puțin 6 caractere.";
    } elseif ($parola !== $confirma) {
        $eroare = "Parolele nu coincid.";
    } else {
        // Citim utilizatorii existenti
        $fisier = 'data/users.json';
        $utilizatori = [];

        if (file_exists($fisier)) {
            $continut = file_get_contents($fisier);
            $utilizatori = json_decode($continut, true) ?? [];
        }

        // Verificam daca emailul exista deja
        $existaEmail = false;
        foreach ($utilizatori as $u) {
            if ($u['email'] === $email) {
                $existaEmail = true;
                break;
            }
        }

        if ($existaEmail) {
            $eroare = "Această adresă de email este deja înregistrată.";
        } else {
            // Adaugam utilizatorul nou
            $utilizatorNou = [
                'id'    => time(),
                'nume'  => $nume,
                'email' => $email,
                'parola' => password_hash($parola, PASSWORD_DEFAULT),
                'creat_la' => date('Y-m-d H:i:s')
            ];

            $utilizatori[] = $utilizatorNou;
            file_put_contents($fisier, json_encode($utilizatori, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $succes = "Cont creat cu succes! Te poți autentifica acum.";
        }
    }
}
?>

<?php include 'header.php'; ?>

<section class="auth-section">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-icon">
                <i class="ti ti-user-plus"></i>
            </div>
            <h1 class="auth-title">Creează cont</h1>
            <p class="auth-subtitle">Alătură-te comunității SportZone</p>
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
                <label for="nume">Nume complet</label>
                <div class="input-wrap">
                    <i class="ti ti-user"></i>
                    <input type="text" id="nume" name="nume" placeholder="Ion Popescu"
                           value="<?= htmlspecialchars($_POST['nume'] ?? '') ?>" required>
                </div>
            </div>

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
                    <input type="password" id="parola" name="parola" placeholder="Minim 6 caractere" required>
                    <button type="button" class="toggle-pass" onclick="togglePass('parola', this)">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirma">Confirmă parola</label>
                <div class="input-wrap">
                    <i class="ti ti-lock-check"></i>
                    <input type="password" id="confirma" name="confirma" placeholder="Repetă parola" required>
                    <button type="button" class="toggle-pass" onclick="togglePass('confirma', this)">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-auth">
                <i class="ti ti-user-plus"></i>
                Creează cont
            </button>

        </form>

        <p class="auth-switch">
            Ai deja cont? <a href="login.php">Autentifică-te</a>
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