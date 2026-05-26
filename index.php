<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportZone - Echipament de performanță</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
</head>
<body>

<?php session_start(); ?>
<?php include 'header.php'; ?>

<main>
    <h1>DAW-241</h1>
    <?php
        $mesaj = "Salut in consola";
        echo "<script>console.log('$mesaj');</script>";
    ?>
</main>

<script src="js/script.js"></script>
<script>
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobileNav');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('open');
        mobileNav.classList.toggle('open');
    });
</script>
</body>
</html>
