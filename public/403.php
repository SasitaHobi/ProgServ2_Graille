<?php

// Démarre la session
session_start();

// Constantes et liens
require __DIR__ . '/../src/utils/autoloader.php';
require_once __DIR__ . '/assets/translations.php';
require_once __DIR__ . '/assets/language.php';



// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: /auth/login.php');
    exit();
}

// Refuser l'accès et afficher un message d'erreur avec un code 403 Forbidden
http_response_code(403);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title><?= $text_translations[$language]['403Title'] ?> ></title>
</head>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['403H1'] ?></h1>

        <p><?= $text_translations[$language]['403Text'] ?></p>

        <p><a href="index.php"><?= $text_translations[$language]['403Back'] ?></a></p>
    </main>
</body>

</html>