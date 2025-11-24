<?php
const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

// Documentation : https://www.php.net/manual/fr/function.parse-ini-file.php
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

session_start();
// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: auth/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

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
            name VARCHAR(40) NOT NULL,
            peremption DATE NOT NULL,
            shop VARCHAR(20),
            qty FLOAT NOT NULL,
            unit VARCHAR(10) NOT NULL,
            spot VARCHAR(20) NOT NULL
);";

$stmt = $pdo->prepare($sql);

$stmt->execute();

// Définition de la requête SQL pour récupérer tous les aliments
$sql = "SELECT * FROM food WHERE user_id= :user_id";
$stmt->bindValue(':user_id', $user_id);

$stmt = $pdo->prepare($sql);

$stmt->execute();

$food = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <title><?= $text_translations[$language]['indexTitle'] ?></title>
</head>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['indexH1'] ?></h1>

        <h2><?= $text_translations[$language]['indexH2'] ?></h2>

        <p><a href="create.php"><button><?= $text_translations[$language]['indexButton'] ?></button></a></p>

        <table>
            <thead>
                <tr>
                    <th><?= $att_translations[$language]['name'] ?></th>
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
                        <!-- à voir si ?? '' marche -->
                        <td><?= htmlspecialchars($att_translations[$language][$f['name']] ?? '') ?></td>
                        <td><?= htmlspecialchars($att_translations[$language][$f['peremption']] ?? '') ?></td>
                        <td><?= htmlspecialchars($att_translations[$language][$f['shop']]) ?></td>
                        <!-- test shop -->
                        <td><?php if (isset($f['shop'])) {
                                echo  htmlspecialchars($att_translations[$language][$f['shop']]);
                            } else {
                                echo "";
                            }
                            ?></td>


                        <td><?= htmlspecialchars($att_translations[$language][$f['qty']]) ?></td>
                        <td><?= htmlspecialchars($att_translations[$language][$f['unit']]) ?></td>
                        <td><?= htmlspecialchars($att_translations[$language][$f['spot']]) ?></td>
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
                        <td>
                            <a href="/public/admin2.php?id=<?= htmlspecialchars($f["id"]) ?>">
                                <button type="button"><?= $text_translations[$language]['adminView'] ?></button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

</html>