<?php
// Constantes
const DATABASE_FILE = __DIR__ . '/../../users.db';

// Démarre la session
session_start();

// Initialise les variables
$error = '';
$success = '';

// Traiter le formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation des données
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $error = $error_translation[$language]['registerEmpty'];;
    } elseif ($password !== $confirmPassword) {
        $error = $error_translation[$language]['registerPwdNoMatch'];;
    } elseif (strlen($password) < 8) {
        $error = $error_translation[$language]['registerPwdShort'];;
    } else {
        try {
            // Connexion à la base de données
            $pdo = new PDO('sqlite:' . DATABASE_FILE);

            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user) {
                $error = $error_translation[$language]['registerUserTkn'];
            } else {
                // Hacher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insérer le nouvel utilisateur
                $stmt = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
                $stmt->execute([
                    'username' => $username,
                    'password' => $hashedPassword,
                    'role' => 'user' // Par défaut, les nouveaux utilisateurs ont le rôle "user"
                ]);

                $success = $error_translation[$language]['registerSuccess'];
            }
        } catch (PDOException $e) {
            $error = $error_translation[$language]['registerFail'] . $e->getMessage();
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
    <title><?=$text_translations[$language]['registerTitle']?></title>
</head>

<body>
    <main class="container">
        <h1><?=$text_translations[$language]['registerH1']?></h1>

        <?php if ($error) { ?>
            <p><strong><?=$text_translations[$language]['registerError']?></strong> <?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <?php if ($success) { ?>
            <p><strong><?=$text_translations[$language]['registerSuccess']?></strong> <?= htmlspecialchars($success) ?></p>
            <p><a href="login.php"><?=$text_translations[$language]['registerLogin']?></a></p>
        <?php } ?>

        <form method="post">
            <label for="username">
                <?=$text_translations[$language]['registerUsername']?>
                <input type="text" id="username" name="username" required autofocus>
            </label>

            <label for="password">
                <?=$text_translations[$language]['registerPwd']?>
                <input type="password" id="password" name="password" required minlength="8">
            </label>

            <label for="confirm_password">
                <?=$text_translations[$language]['registerConfirm']?>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </label>

            <button type="submit"><?=$text_translations[$language]['registerSubmit']?></button>
        </form>

        <p><?=$text_translations[$language]['registerAccount']?><a href="login.php"><?=$text_translations[$language]['registerLogin']?></a></p>

        <p><a href="index.php"><?=$text_translations[$language]['registerBack']?></a></p>
    </main>
</body>

</html>
