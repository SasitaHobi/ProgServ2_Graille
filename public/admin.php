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


        <table>
            <thead>
                <tr>
                    <th><?= $att_translations[$language]['name'] ?></th>
                    <th><?= $att_translations[$language]['userId'] ?></th>
                    <th><?= $att_translations[$language]['peremption'] ?></th>
                    <th><?= $att_translations[$language]['shop'] ?></th>
                    <th><?= $att_translations[$language]['qty'] ?></th>
                    <th><?= $att_translations[$language]['unit'] ?></th>
                    <th><?= $att_translations[$language]['spot'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($food as $f) { ?>
                    <tr>
                        <td><?= htmlspecialchars($f['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['userId'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['peremption'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['shop'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['qty'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['unit'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['spot'] ?? '') ?></td>
                        <td>
                            <a href="view.php?id=<?= htmlspecialchars($f["id"]) ?>">
                                <button type="button"><?= $text_translations[$language]['viewButton'] ?></button>
                            </a>
                        </td>
                        <td>
                            <a href="delete.php?id=<?= htmlspecialchars($f["id"]) ?>">
                                <button type="button"><?= $text_translations[$language]['viewDelete'] ?></button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

</html>