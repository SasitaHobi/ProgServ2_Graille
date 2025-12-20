<?php

// Démarre la session
session_start();

// Constantes et liens
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';


// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: auth/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];


//Suppression de l'aliment choisi
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
