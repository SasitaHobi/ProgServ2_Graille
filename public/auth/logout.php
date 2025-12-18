<?php

require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

// Démarrer la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: login.php');
    exit();
}

// Détruit la session
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title><?= $text_translations[$language]['logoutTitle'] ?></title>
</head>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['logoutH1'] ?></h1>

        <p><?= $text_translations[$language]['logoutText'] ?></p>

        <a href="../index.php">
            <button type="button"><?= $text_translations[$language]['logoutBack'] ?></button>
        </a>

        <a href="login.php">
            <button type="button"><?= $text_translations[$language]['registerLogin'] ?></button>
        </a>

    </main>
</body>

</html>