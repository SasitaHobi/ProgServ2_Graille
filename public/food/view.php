<?php

// Démarre la session
session_start();

// liens
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use Food\FoodManager;
use Food\Food;

$foodManager = new FoodManager();

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// On vérifie si l'ID de l'aliment est passé dans l'URL
if (isset($_GET["id"])) {
    $foodId = $_GET["id"];

    // On récupère l'aliment correspondant à l'ID
    $food = $foodManager->getFoodById($foodId);

    if (!$food) {
        header("Location: index.php");
        exit();
    }
} else {
    // Si l'ID n'est pas passé dans l'URL, on redirige vers la page d'accueil
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">

<?php
// Affiche le head commun avec les paramètres de la page de détail.
render('head', [
    'title' => $text_translations[$language]['viewTitle'],
    'colorScheme' => true,
    'customCss' => true,
]);
?>

<body>

    <?php
    // Affiche le header commun aux pages de gestion des aliments.
    render('header', [
        'text_translations' => $text_translations,
        'language' => $language,
    ]);
    ?>

    <main class="container">
        <h1><?= $text_translations[$language]['viewH1'] ?></h1>

        <a href="edit.php?id=<?= $food->getId() ?>">
            <button type="button"><?= $text_translations[$language]['editH1'] ?></button>
        </a>
        <a href="delete.php?id=<?= $food->getId() ?>">
            <button type="button"><?= $text_translations[$language]['viewDelete'] ?></button>
        </a>

        <p><?= $text_translations[$language]['viewText'] ?></p>


        <form>
            <label for="name"><?= $att_translations[$language]['name'] ?></label>
            <input type="text" id="name" value="<?= htmlspecialchars($food->getName()) ?>" disabled />

            <label for="peremption"><?= $att_translations[$language]['peremption'] ?></label>
            <input type="date" id="peremption" value="<?= htmlspecialchars($food->getPeremption()->format('Y-m-d')) ?>" disabled />

            <label for="shop"><?= $att_translations[$language]['shop'] ?></label>
            <input type="text" id="shop" value="<?= htmlspecialchars($food->getShop() ?? '') ?>" disabled />

            <label for="qty"><?= $att_translations[$language]['qty'] ?></label>
            <input type="number" id="qty" value="<?= htmlspecialchars($food->getQty()) ?>" disabled />

            <label for="unit"><?= $att_translations[$language]['unit'] ?></label>
            <select id="unit" disabled>
                <?php foreach (Food::UNIT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food->getUnit() == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="spot"><?= $att_translations[$language]['spot'] ?></label>
            <select id="spot" disabled>
                <?php foreach (Food::SPOT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food->getSpot() == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

        </form>
    </main>
    <?php
    // Affiche le footer commun de la plateforme.
    render('footer');
    ?>
</body>

</html>