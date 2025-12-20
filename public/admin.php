<?php

// Démarre la session
session_start();

// Constantes et liens
const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';
require_once __DIR__ . '/assets/translations.php';
require_once __DIR__ . '/assets/language.php';

// Connexion à la base de données
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

if (!$config) {
    throw new Exception("Erreur lors de la lecture du fichier de configuration : " . DATABASE_CONFIGURATION_FILE);
}

$host = $config['host'];
$port = $config['port'];
$database = $config['database'];
$username = $config['username'];
$password = $config['password'];

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: ../auth/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];


$pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);

// Création de la base de données si elle n'existe pas
$sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Sélection de la base de données
$sql = "USE `$database`;";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// rediriger vers page 403 lorsque qu'un utilisateur non-admin tente d'aller sur la page admin
$sql ="SELECT role FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user ||$user['role'] !== 'admin') {
    header('Location: 403.php');
    exit();
}

// Création de la table si elle n'existe pas
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

// Définition de la requête SQL pour récupérer tous les aliments
$sql = "SELECT * FROM food";
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
    <link rel="stylesheet" href="css/custom.css">

    <title><?= $text_translations[$language]['adminTitle'] ?></title>
</head>

<body>

    <header>
        <a href="../index.php">
            <button type="button"><?= $text_translations[$language]['logoutBack'] ?></button>
        </a>

        <a href="../auth/logout.php">
            <button type="button"><?= $text_translations[$language]['registerLogout'] ?></button>
        </a>
    </header>

    <main class="container">
        <h1><?= $text_translations[$language]['adminView'] ?></h1>

        <p><?= $text_translations[$language]['adminP'] ?></p>

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
                        <td><?= htmlspecialchars($f['user_id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['peremption'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['shop'] ?? '') ?></td>
                        <td><?= htmlspecialchars($f['qty'] ?? '') ?></td>
                        <td><?= htmlspecialchars($att_translations[$language][$f['unit']] ?? $f['unit']) ?></td>
                        <td><?= htmlspecialchars($att_translations[$language][$f['spot']] ?? $f['spot']) ?></td>
                        <td>
                            <a href="food/view.php?id=<?= htmlspecialchars($f["id"]) ?>">
                                <button type="button"><?= $text_translations[$language]['viewButton'] ?></button>
                            </a>
                        </td>
                        <td>
                            <a href="food/delete.php?id=<?= htmlspecialchars($f["id"]) ?>">
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