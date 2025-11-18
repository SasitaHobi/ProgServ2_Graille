<?php
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use Food\FoodManager;
use Food\Food;

session_start();
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: 403.php');
    exit();
}
$user_id = $_SESSION['user_id'];

$foodManager = new FoodManager();
$food;

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

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <title><?=$text_translations[$language]['viewTitle']?></title>
</head>

<body>
    <main class="container">
        <h1><?=$text_translations[$language]['viewH1']?></h1>
        <p><a href="index.php"><?=$text_translations[$language]['viewBack']?></a></p>
        <p><?=$text_translations[$language]['viewText']?></p>

        
        <form>
            <label for="name"><?=$att_translations[$language]['name']?></label>
            <input type="text" id="name" value="<?= htmlspecialchars($food->getName())?>" disabled />

          
            <label for="peremption"><?=$att_translations[$language]['peremption']?></label>
            <input type="date" id="peremption" value="<?= htmlspecialchars ($food->getPeremption()->format('Y-m-d')) ?>" disabled />

            <label for="shop"><?=$att_translations[$language]['shop']?></label>
            <input type="text" id="shop" value="<?= htmlspecialchars($food->getShop())?>" disabled />

            <label for="qty"><?=$att_translations[$language]['qty']?></label>
            <input type="number" id="qty" value="<?= htmlspecialchars($food->getQty())?>" disabled />

            <label for="unit"><?=$att_translations[$language]['unit']?></label>
            <select id="unit" disabled>
                <?php foreach (Food::UNIT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food->getUnit() == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="spot"><?=$att_translations[$language]['spot']?></label>
            <select id="spot" disabled>
                <?php foreach (Food::SPOT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food->getSpot() == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <a href="delete.php?id=<?= htmlspecialchars($food["id"]) ?>">
                <button type="button"><?=$text_translations[$language]['viewDelete']?></button>
            </a>
            <a href="edit.php?id=<?= htmlspecialchars($food["id"]) ?>">
                <button type="button"><?=$text_translations[$language]['viewUpdate']?></button>
            </a>
        </form>
    </main>
</body>

</html>