<?php

require_once 'assets/translations.php';
require_once 'assets/language.php';


// Gestion de la suppression du cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cookie'])) {
    setcookie(COOKIE_NAME, '', time() - 3600);
    header('Location: index.php');
    exit;
}

// Changement de langue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language'])) {
    $language = $_POST['language'];

    setcookie(COOKIE_NAME, $language, time() + COOKIE_LIFETIME);

    header('Location: index.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <title><?=$text_translations[$language]['homeTitle']?></title>
</head>

<body>

    <!-- préférence de langue -->
    <header>
        <form method="POST">
            <label for="language">
                <?= $text_translations[$language]['language'] ?? "Langue" ?>
            </label>

            <select name="language" id="language">
                <option value="fr" <?= $language === 'fr' ? 'selected' : '' ?>>FR</option>
                <option value="en" <?= $language === 'en' ? 'selected' : '' ?>>EN</option>
            </select>

            <button type="submit">OK</button>
        </form>

        <form method="POST">
            <button type="submit" name="delete_cookie"><?=$text_translations[$language]['viewDelete']?></button>
        </form>
    </header>

    <!-- accueil normal -->
    <main class="container">
        <h1><?=$text_translations[$language]['homeH1']?></h1>

        <p><?=$text_translations[$language]['homeText']?></p>


        <p><a href="food/index.php"><button><?=$text_translations[$language]['homeButton']?></button></a></p>
    </main>
</body>

</html>