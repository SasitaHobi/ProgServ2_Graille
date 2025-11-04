<?php
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use Food\Food;
use Food\FoodManager;

$foodManager = new FoodManager();

// vérification de l'ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

// récupération des données actuelles de l'aliment
$food = $foodManager->getFood($id);

if (!$food) {
    echo $errors_translations[$language]['editFood'];
    exit();
}

// mettre à jour lorsque le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $updatedData = [
        "name" => $_POST["name"] ?? "",
        "shop" => $_POST["shop"] ?? "",
        "qty" => $_POST["qty"] ?? 0,
        "unit" => $_POST["unit"] ?? "",
        "spot" => $_POST["spot"] ?? "",
        "peremption" => $_POST["peremption"] ?? ""
    ];
}
?>

<!-- partie html -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$text_translations[$language]['editTitle']?></title>
</head>

<body>
    <main class="container">
        <h1><?=$text_translations[$language]['viewBack']?></h1>
        <p><a href="index.php"><?=$att_translations[$language]['spot']?></a></p>

        <form method="POST">
            <label for="name"><?=$att_translations[$language]['name']?></label>
            <input type="text" name="name" id="id" value="<?= htmlspecialchars($food['name']) ?>" required />

            <label for="shop"><?=$att_translations[$language]['shop']?></label>
            <input type="text" name="shop" id="shop" value="<?= htmlspecialchars($food['shop']) ?>" required />

            <label for="qty"><?=$att_translations[$language]['qty']?></label>
            <input type="number" name="qty" id="qty" value="<? htmlspecialchars($food['qty']) ?>" min="0" required />

            <label for="unit"><?=$att_translations[$language]['unit']?></label>
            <select name="unit" id="unit">
                <?php foreach (Food::UNIT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food['unit'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="spot"><?=$att_translations[$language]['spot']?></label>
            <select name="spot" id="spot">
                <?php foreach (Food::SPOT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food['spot'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="peremption"><?=$att_translations[$language]['peremption']?></label>
            <input type="date" name="peremption" id="peremption" value="<?= htmlspecialchars($food['peremption']) ?>" />

            <div style="margin-top: 1rem; display: flex; gap: .5em;">
                <button type="submit"><?=$text_translations[$language]['editH1']?></button>
                <a href="view.php?id=<?= htmlspecialchars($food['id']) ?>"><button type="button" class="annulation">$text_translations[$language]['editCancel']</button></a>
            </div>
        </form>
    </main>
</body>

</html>