<?php
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use Food\FoodManager;
use Food\Food;
use User\User;
use User\UsersManager;

// Constantes
const DATABASE_FILE = __DIR__ . '/../users.db';

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: auth/login.php');
    exit();
}

// Vérifie si l'utilisateur a le bon rôle
if ($_SESSION['role'] !== 'admin') {
    // Redirige vers la page 403 si l'utilisateur n'est pas admin
    header('Location: 403.php');
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

    <title><?= $text_translations[$language]['adminTitle'] ?></title>
</head>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['adminView'] ?></h1>

        <p><?= $text_translations[$language]['adminP'] ?></p>
        </head>

        <p><a href="index.php"><?= $text_translations[$language]['viewBack'] ?></a></p>
        <p><?= $text_translations[$language]['viewText'] ?></p>


        <form>

            <label for="username"><?= $att_translations[$language]['username'] ?></label>
            <input type="text" id="username" value="<?= htmlspecialchars($food->getUserId()) ?>" disabled />

            <label for="name"><?= $att_translations[$language]['name'] ?></label>
            <input type="text" id="name" value="<?= htmlspecialchars($food->getName()) ?>" disabled />

            <label for="peremption"><?= $att_translations[$language]['peremption'] ?></label>
            <input type="date" id="peremption" value="<?= htmlspecialchars($food->getPeremption()->format('Y-m-d')) ?>" disabled />

            <label for="shop"><?= $att_translations[$language]['shop'] ?></label>
            <input type="text" id="shop" value="<?= htmlspecialchars($food->getShop()) ?>" disabled />

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

            <a href="delete.php?id=<?= htmlspecialchars($food["id"]) ?>">
                <button type="button"><?= $text_translations[$language]['viewDelete'] ?></button>
            </a>
            <a href="edit.php?id=<?= htmlspecialchars($food["id"]) ?>">
                <button type="button"><?= $text_translations[$language]['viewUpdate'] ?></button>
            </a>
        </form>
    </main>
</body>

</html>