<?php

// Constantes pour la gestion des cookies
const COOKIE_LIFETIME = 60 * 60 * 24 * 30; // 30 jours
const COOKIE_NAME = 'language';
const DEFAULT_LANGUAGE = 'fr';

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

// Récupération de la préférence utilisateur depuis le cookie
$language = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANGUAGE;
?>



<!DOCTYPE html>
<html lang="fr">

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
    <button type="submit" name="delete_cookie">Supprimer</button>
</form>

</html>