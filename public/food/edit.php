<?php
// Démarre la session
session_start();

// liens
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use Food\Food;
use Food\FoodManager;


// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

// déclaration des variables
$user_id = $_SESSION['user_id'];
$admin_id = 1;
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

// si l'aliment n'appartient pas à l'utilisateur connecté et que l'utilisateur n'est pas l'admin
if (isset($food) && $food->getUserId() !== $user_id && $user_id !== $admin_id) {
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
        $error = $errors_translations[$language]['nameRequired'];
    } elseif (!is_numeric($qty) || $qty < 0) {
        $error = $errors_translations[$language]['qtyInvalid'];
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

        // si le changement a marché, on redirige vers la page view de l'aliment
        if ($success) {
            header("Location: view.php?id=" . $id);
            exit();
        } else {
            $error = $errors_translations[$language]['updateFailed'];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<?php
// Affiche le head commun avec les paramètres de la page de modification.
render('head', [
    'title' => $text_translations[$language]['editTitle'],
    'colorScheme' => true,
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
        <h1><?= $text_translations[$language]['editTitle'] ?></h1>

        <form method="POST">
            <label for="name"><?= $att_translations[$language]['name'] ?></label>
            <input type="text" name="name" id="id" value="<?= htmlspecialchars($food->getName()) ?>" required />

            <label for="peremption"><?= $att_translations[$language]['peremption'] ?></label>
            <input type="date" name="peremption" id="peremption" value="<?= htmlspecialchars($food->getPeremption()->format('Y-m-d')) ?>" />

            <label for="shop"><?= $att_translations[$language]['shop'] ?></label>
            <!-- pour éviter une erreur "deprecated string" quand l'utilisateur ne rentre pas de shop -->
            <?php $shopValue = $food->getShop() ?? ''; ?>
            <input type="text" name="shop" id="shop" value="<?= htmlspecialchars($shopValue) ?>" />

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