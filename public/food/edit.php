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
