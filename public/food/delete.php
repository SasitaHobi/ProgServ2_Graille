<?php
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

session_start();
$user_id = $_SESSION['user_id'];

use Food\FoodManager;

$foodManager = new FoodManager();

if (isset($_GET["id"])) {
    $foodId = $_GET["id"];

    $foodManager->removeFood($foodId);

    header("Location: index.php");
    exit();
} else {
    // Si l'ID n'est pas passé dans l'URL, redirection vers la page d'accueil
    header("Location: index.php");
    exit();
}
