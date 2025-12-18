<?php
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use Food\Food;
use Food\FoodManager;

session_start();

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$foodManager = new FoodManager();
$error = null;
$success = false;

// Vérification de l'ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

// Récupération des données actuelles de l'aliment
$food = $foodManager->getFoodById($id);

if (!$food) {
    echo $error_translations[$language]['editFood'];
    exit();
}

// Vérification que l'aliment appartient à l'utilisateur connecté
if (isset($food) && $food->getUserId() !== $user_id) {
    header("Location: index.php");
    exit();
}

// Mettre à jour lorsque le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation basique
    $name = trim($_POST["name"] ?? "");
    $shop = trim($_POST["shop"] ?? "");
    $qty = $_POST["qty"] ?? 0;
    $unit = $_POST["unit"] ?? "";
    $spot = $_POST["spot"] ?? "";
    $peremption = $_POST["peremption"] ?? "";

    // Validation
    if (empty($name)) {
        $error = $errors_translations[$language]['nameRequired'] ?? "Le nom est requis";
    } elseif (!is_numeric($qty) || $qty < 0) {
        $error = $errors_translations[$language]['qtyInvalid'] ?? "La quantité doit être un nombre positif";
    } else {
        $updatedData = [
            "name" => $name,
            "shop" => $shop,
            "qty" => (float) $qty,
            "unit" => $unit,
            "spot" => $spot,
            "peremption" => $peremption
        ];

        // Mise à jour dans la base de données
        $success = $foodManager->updateFood($id, $updatedData);

        if ($success) {
            header("Location: view.php?id=" . $id);
            exit();
        } else {
            $error = $errors_translations[$language]['updateFailed'] ?? "Échec de la mise à jour";
        }
    }
}
?>

<!-- partie html -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <title><?= $text_translations[$language]['editTitle'] ?></title>
</head>

<body>

    <header>
        <a href="../index.php">
            <button type="button"><?= $text_translations[$language]['logoutBack'] ?></button>
        </a>

        <a href="index.php">
            <button type="button"><?= $text_translations[$language]['homeButton'] ?></button>
        </a>

        <a href="../auth/logout.php">
            <button type="button"><?= $text_translations[$language]['registerLogout'] ?></button>
        </a>
    </header>

    <main class="container">
        <h1><?= $text_translations[$language]['editTitle'] ?></h1>

        <form method="POST">
            <label for="name"><?= $att_translations[$language]['name'] ?></label>
            <input type="text" name="name" id="id" value="<?= htmlspecialchars($food->getName()) ?>" required />

            <label for="peremption"><?= $att_translations[$language]['peremption'] ?></label>
            <input type="date" name="peremption" id="peremption" value="<?= htmlspecialchars($food->getPeremption()->format('Y-m-d')) ?>" />

            <label for="shop"><?= $att_translations[$language]['shop'] ?></label>
            <input type="text" name="shop" id="shop" value="<?= htmlspecialchars($food->getShop()) ?>" required />

            <label for="qty"><?= $att_translations[$language]['qty'] ?></label>
            <input type="number" name="qty" id="qty" value="<?= htmlspecialchars($food->getQty()) ?>" min="0" required />

            <label for="unit"><?= $att_translations[$language]['unit'] ?></label>
            <select name="unit" id="unit">
                <?php foreach (Food::UNIT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food->getUnit() == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="spot"><?= $att_translations[$language]['spot'] ?></label>
            <select name="spot" id="spot">
                <?php foreach (Food::SPOT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food->getSpot() == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <!-- bouton pour sauvegarder changements -->
            <button type="submit" class="save">
                <?= $text_translations[$language]['editSave'] ?>
            </button>

            <!-- bouton pour annuler changements -->
            <a href="view.php?id=<?= htmlspecialchars($food->getId()) ?>">
                <button type="button" class="cancel">
                    <?= $text_translations[$language]['editCancel'] ?? 'Annuler' ?>
                </button>
            </a>
            </div>
        </form>
    </main>
</body>

</html>