<?php

// Démarre la session
session_start();

// Constantes et liens
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';

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


// Si l'utilisateur est déjà connecté, le rediriger vers son gestionnaire d'aliments
if (isset($_SESSION['user_id'])) {
    header('Location: ../food/index.php');
    exit();
}

// Initialise la variable contenant les erreurs
$error = '';


// Traite le formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameUser = $_POST['username'];
    $passwordUser = $_POST['password'];

    if (empty($usernameUser) || empty($passwordUser)) {
        $error = $error_translations[$language]['registerEmpty'];
    } else {
        try {
            $pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
                $username,
                $password
            );

            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :usernameUser');
            $stmt->execute(['usernameUser' => $usernameUser]);
            $user = $stmt->fetch();

            if ($user && password_verify($passwordUser, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: ../food/index.php');
                exit();
            } else {
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

<?php
// Affiche le head commun avec le titre de la page de connexion.
render('head', [
    'title' => $text_translations[$language]['loginTitle'],
]);
?>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['loginH1'] ?></h1>

        <?php if ($error) { ?>
            <p><strong><?= $text_translations[$language]['loginError'] ?></strong> <?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <form method="post">
            <label for="username">
                <?= $text_translations[$language]['loginUsername'] ?>
                <input type="text" id="username" name="username" required autofocus>
            </label>

            <label for="password">
                <?= $text_translations[$language]['loginPwd'] ?>
                <input type="password" id="password" name="password" required>
            </label>

            <button type="submit"><?= $text_translations[$language]['loginSubmit'] ?></button>
        </form>

        <p><?= $text_translations[$language]['loginNoAccount'] ?>
            <a href="register.php"><?= $text_translations[$language]['loginCreate'] ?></a>
        </p>

        <p><a href="../index.php"><?= $text_translations[$language]['loginBack'] ?></a></p>
    </main>
</body>

</html>