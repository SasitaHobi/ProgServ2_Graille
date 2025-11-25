<?php
session_start();

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../src/config/database.ini';
const MAIL_CONFIGURATION_FILE     = __DIR__ . '/../../src/config/mail.ini';
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Constantes
const DATABASE_FILE = __DIR__ . '/../users.db';

// Connexion à la base de données
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

if (isset($_SESSION['user_id'])) {
    header('Location: ../food/index.php');
    exit();
}

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


// Initialise les variables
$error = '';
$success = '';

// Traiter le formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation des données
    if (empty($username) || empty($password) || empty($confirmPassword)|| empty($email)) {
        $error = $error_translations[$language]['registerEmpty'];;
    } elseif ($password !== $confirmPassword) {
        $error = $error_translations[$language]['registerPwdNoMatch'];;
    } elseif (strlen($password) < 8) {
        $error = $error_translations[$language]['registerPwdShort'];;
    } else {
        try {

            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user) {
                $error = $error_translations[$language]['registerUserTkn'];
            } else {
                // Hacher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insérer le nouvel utilisateur
                $stmt = $pdo->prepare('INSERT INTO users (username,email, password, role) VALUES (:username, :email, :password, :role)');
                $stmt->execute([
                    'username' => $username,
                    'email'=> $email,
                    'password' => $hashedPassword,
                    'role' => 'user' // Par défaut, les nouveaux utilisateurs ont le rôle "user"
                ]);

                $success = $error_translations[$language]['registerSuccess'];

                //Envoi d'un e-mail de confirmation de création de compte
                $mailConfig = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

                if ($mailConfig) {
                    $host_mail         = $mailConfig['host'];
                    $port_mail         = filter_var($mailConfig['port'], FILTER_VALIDATE_INT);
                    $authentication    = filter_var($mailConfig['authentication'], FILTER_VALIDATE_BOOLEAN);
                    $smtp_username     = $mailConfig['username'];
                    $smtp_password     = $mailConfig['password'];
                    $from_email        = $mailConfig['from_email'];
                    $from_name         = $mailConfig['from_name'];

                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host       = $host_mail;
                        $mail->Port       = $port_mail;
                        $mail->SMTPAuth   = $authentication;
                        if ($authentication) {
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                            $mail->Username = $smtp_username;
                            $mail->Password =$smtp_password;
                        }
                        $mail->CharSet    = "UTF-8";
                        $mail->Encoding   = "base64";


                        $mail->setFrom($from_email, $from_name);
                        $mail->addAddress($email, $username);

                        $mail->isHTML(true);
                        $mail->Subject = 'Création de votre compte';
                        $mail->Body    = 'Félicitations, votre compte a bien été créé';
                        $mail->AltBody = 'Félicitations, votre compte a bien été créé';

                        $mail->send();
                    } catch (Exception $e) {
                        // On ne bloque pas l'inscription, on log juste l'erreur
                        error_log("Erreur envoi mail de création de compte : " . $mail->ErrorInfo);
                    }
                } else {
                    error_log("Erreur lors de la lecture du fichier de configuration : " . MAIL_CONFIGURATION_FILE);
                }
            }
        } catch (PDOException $e) {
            $error = $error_translations[$language]['registerFail'] . $e->getMessage();
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
    <title><?= $text_translations[$language]['registerTitle'] ?></title>
</head>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['registerH1'] ?></h1>

        <?php if ($error) { ?>
            <p><strong><?= $text_translations[$language]['registerError'] ?></strong> <?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <?php if ($success) { ?>
            <p><strong><?= $text_translations[$language]['registerSuccess'] ?></strong> <?= htmlspecialchars($success) ?></p>
            <p><a href="login.php"><?= $text_translations[$language]['registerLogin'] ?></a></p>
        <?php } ?>

        <form method="post">
            <label for="username">
                <?= $text_translations[$language]['registerUsername'] ?>
                <input type="text" id="username" name="username" required autofocus>
            </label>
            <label for="email">
                <?= $text_translations[$language]['registerEmail'] ?>
                <input type="email" id="email" name="email" required autofocus>
            </label>

            <label for="password">
                <?= $text_translations[$language]['registerPwd'] ?>
                <input type="password" id="password" name="password" required minlength="8">
            </label>

            <label for="confirm_password">
                <?= $text_translations[$language]['registerConfirm'] ?>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </label>

            <button type="submit"><?= $text_translations[$language]['registerSubmit'] ?></button>
        </form>

        <p><?= $text_translations[$language]['registerAccount'] ?><a href="login.php"><?= $text_translations[$language]['registerLogin'] ?></a></p>

        <p><a href="index.php"><?= $text_translations[$language]['registerBack'] ?></a></p>
    </main>
</body>

</html>