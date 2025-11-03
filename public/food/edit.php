<?php
require __DIR__ . '/../../src/utils/autoloader.php';

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
    echo "<p>L'aliment est introuvable</p>";
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
        "peremption" => $_POST["peremption"] ?? "",
        "notes" => $_POST["notes"] ?? ","
    ];
}
?>

// partie html

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un aliment</title>
</head>

<body>
    <main class="container">
        <h1>Modifier un aliment</h1>
        <p><a href="index.php">Retour à l'accueil</a></p>

        <form method="POST">
            <label for="name">Nom de l'aliment</label>
            <input type="text" name="name" id="id" value="<?= htmlspecialchars($food['name']) ?>" required />

            <label for="shop">Magasin :</label>
            <input type="text" name="shop" id="shop" value="<?= htmlspecialchars($food['shop']) ?>" required />

            <label for="qty">Quantité :</label>
            <input type="number" name="qty" id="qty" value="<? htmlspecialchars($food['qty']) ?>" min="0" required />

            <label for="unit">Unité :</label>
            <select name="unit" id="unit">
                <?php foreach (Food::UNIT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food['unit'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="spot">Emplacement :</label>
            <select name="spot" id="spot">
                <?php foreach (Food::SPOT as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= $food['spot'] == $key ? 'selected' : '' ?>><?= $value ?></option>
                <?php } ?>
            </select>

            <label for="peremption">Date de péremption :</label>
            <input type="date" name="peremption" id="peremption" value="<?= htmlspecialchars($food['peremption']) ?>" />

            <div style="margin-top: 1rem; display: flex; gap: .5em;">
                <button type="submit">Enregistrer les modification</button>
                <a href="view.php?id=<?= htmlspecialchars($food['id']) ?>"><button type="button" class="annulation">Annuler</button></a>
            </div>
        </form>
    </main>
</body>

</html>