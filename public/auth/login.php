<?php

require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

// Constantes
const DATABASE_FILE = __DIR__ . '/../../users.db';

// Démarre la session
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Initialise les variables
$error = '';

// Traite le formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validation des données
    if (empty($username) || empty($password)) {
        $error = $error_translations[$language]['registerEmpty'];
    } else {
        try {
            // Connexion à la base de données
            $pdo = new PDO('sqlite:' . DATABASE_FILE);

            // Récupérer l'utilisateur de la base de données
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            // Vérifier le mot de passe
            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie - stocker les informations dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Rediriger vers la page d'accueil
                header('Location: ../index.php');
                exit();
            } else {
                // Authentification échouée
                $error = $error_translations[$language]['loginError'];
            }
        } catch (PDOException $e) {
            $error = $error_translations[$language]['loginFail'] . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title><?=$text_translations[$language]['loginTitle']?></title>
</head>

<body>
    <main class="container">
        <h1><?=$text_translations[$language]['loginH1']?></h1>

        <?php if ($error) { ?>
            <p><strong><?=$text_translations[$language]['loginError']?></strong> <?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <form method="post">
            <label for="username">
                <?=$text_translations[$language]['loginUsername']?>
                <input type="text" id="username" name="username" required autofocus>
            </label>

            <label for="password">
                <?=$text_translations[$language]['loginPwd']?>
                <input type="password" id="password" name="password" required>
            </label>

            <button type="submit"><?=$text_translations[$language]['loginSubmit']?></button>
        </form>

        <p><?=$text_translations[$language]['loginNoAccount']?>
        <a href="register.php"><?=$text_translations[$language]['loginCreate']?></a></p>

        <p><a href="../index.php"><?=$text_translations[$language]['loginBack']?></a></p>
    </main>
</body>

</html>
