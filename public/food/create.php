<?php
session_start();

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: ../auth/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// Documentation : https://www.php.net/manual/fr/function.parse-ini-file.php
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);


if (!$config) {
    throw new Exception("Erreur lors de la lecture du fichier de configuration : " . DATABASE_CONFIGURATION_FILE);
}

$host = $config['host'];
$port = $config['port'];
$database = $config['database'];
$username = $config['username'];
$password = $config['password'];

// Documentation :
//   - https://www.php.net/manual/fr/pdo.connections.php
//   - https://www.php.net/manual/fr/ref.pdo-mysql.connection.php
$pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);

// Création de la base de données si elle n'existe pas
$sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Sélection de la base de données
$sql = "USE `$database`;";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$sql = "CREATE TABLE IF NOT EXISTS food (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(40) NOT NULL,
    peremption DATE NOT NULL,
    shop VARCHAR(20),
    qty FLOAT NOT NULL,
    unit VARCHAR(10) NOT NULL,
    spot VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";

$stmt = $pdo->prepare($sql);
$stmt->execute();

// Gère la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire
    $name = $_POST["name"];
    $peremption = $_POST["peremption"];
    $shop = $_POST["shop"];
    $qty = $_POST["qty"];
    $unit = $_POST["unit"];
    $spot = $_POST["spot"];

    $errors = [];

    // à checker

    if (empty($name) || strlen($name) < 2) {
        $errors[] = $error_translation[$language]['createName'];
    }


    if (!empty($shop) && strlen($shop) < 2) {
        $errors[] = $error_translation[$language]['createShop'];
    }

    if ($qty < 0) {
        $errors[] = $error_translation[$language]['createQty'];
    }

    if (empty($unit)) {
        $errors[] = $error_translation[$language]['createUnit'];
    }

    // aussi liste déroulante
    if (empty($spot)) {
        $errors[] = $error_translation[$language]['createSpot'];
    }


    // Si pas d'erreurs, insertion dans la base de données
    if (empty($errors)) {
        $sql = "INSERT INTO food (
            user_id,
            name,
            peremption,
            shop,
            qty,
            unit,
            spot
        ) VALUES (
            :user_id,
            :name,
            :peremption,
            :shop,
            :qty,
            :unit,
            :spot
        )";

        // Préparation de la requête SQL
        $stmt = $pdo->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':peremption', $peremption);

        if (!empty($shop)) {
            $shopParam = $shop; // si shop n'est pas vide, on prend sa valeur
        } else {
            $shopParam = null;  // si shop est vide, on met NULL pour la base
        }
        $stmt->bindValue(':shop', $shopParam, PDO::PARAM_STR);

        $stmt->bindValue(':qty', $qty);
        $stmt->bindValue(':unit', $unit);
        $stmt->bindValue(':spot', $spot);


        $stmt->execute();

        // Redirection vers la page d'accueil avec tous les aliments
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <title><?=$text_translations[$language]['createTitle']?></title>
</head>

<body>
    <main class="container">
        <h1><?=$text_translations[$language]['createH1']?></h1>

        <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
            <?php if (empty($errors)) { ?>
                <p style="color: green;"><?=$text_translations[$language]['createSuccess']?></p>
            <?php } else { ?>
                <p style="color: red;"><?=$text_translations[$language]['createError']?></p>
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>

        <!-- à changer -->
        <form action="create.php" method="POST">
            <label for="name"><?=$att_translations[$language]['name']?></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required minlength="2">

            <label for="peremption"><?=$att_translations[$language]['peremption']?></label>
            <input type="date" id="peremption" name="peremption" value="<?= htmlspecialchars($peremption ?? '') ?>" required>

            <label for="shop"><?=$att_translations[$language]['shop']?></label>
            <input type="text" id="shop" name="shop" value="<?= htmlspecialchars($shop ?? '') ?>">

            <label for="qty"><?=$att_translations[$language]['qty']?></label>
            <input type="number" id="qty" name="qty" value="<?= htmlspecialchars($qty ?? '') ?>" required min="0">

            <label for="unit"><?=$att_translations[$language]['unit']?></label>
            <select id="unit" name="unit" required>
                <option value="pack"><?=$att_translations[$language]['pack']?></option>
                <option value="piece"><?=$att_translations[$language]['piece']?></option>
                <option value="ml"><?=$att_translations[$language]['ml']?></option>
                <option value="l"><?=$att_translations[$language]['l']?></option>
                <option value="g"><?=$att_translations[$language]['g']?></option>
                <option value="kg"><?=$att_translations[$language]['kg']?></option>
            </select>

            <label for="spot"><?=$att_translations[$language]['spot']?></label>
            <select id="spot" name="spot" required>
                <option value="cupboard"><?=$att_translations[$language]['cupboard']?></option>
                <option value="fridge"><?=$att_translations[$language]['fridge']?></option>
                <option value="freezer"><?=$att_translations[$language]['freezer']?></option>
                <option value="cellar"><?=$att_translations[$language]['cellar']?></option>

            </select>
            <button type="submit"><?=$text_translations[$language]['createH1']?></button>
        </form>
    </main>
</body>

</html>