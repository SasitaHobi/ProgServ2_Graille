<?php
session_start();

require __DIR__ . '/../src/utils/autoloader.php';
require_once 'assets/translations.php';
require_once 'assets/language.php';

use Database\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../src/config/mail.ini';

const DATABASE_FILE = __DIR__ . '/../../users.db';

$db = new Database();

// Gestion de la suppression du cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_cookie'])) {
    setcookie(COOKIE_NAME, '', time() - 3600);
    header('Location: index.php');
    exit;
}

// Changement de langue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['language'])) {
    $language = $_POST['language'];

    setcookie(COOKIE_NAME, $language, time() + COOKIE_LIFETIME);

    header('Location: index.php');
    exit;
}

// Envoi d'un e-mail
$config = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

if (!$config) {
    throw new Exception("Erreur lors de la lecture du fichier de configuration : " . MAIL_CONFIGURATION_FILE);
}

$host = $config['host'];
$port = filter_var($config['port'], FILTER_VALIDATE_INT);
$authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
$username = $config['username'];
$password= $config['password'];
$from_email = $config['from_email'];
$from_name = $config['from_name'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->Port = $port;
    $mail->SMTPAuth = $authentication;
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";

    $mail->setFrom($from_email, $from_name);
    $mail->addAddress('','');

    $mail->isHTML(true);
    $mail->Subject = 'Création de votre compte';
    $mail->Body = 'Félicitation, votre compte a bien été créé';
    $mail->AltBody = 'Félicitation, votre compte a bien été créé';
    
    $mail->send();

    echo 'Mail envoyé';
} catch (Exception $e) {
    echo "Le message n'a pas pu être envoyé. Mailer Error : {$mail->ErrorInfo}";
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <title><?= $text_translations[$language]['homeTitle'] ?></title>
</head>

<body>

    <!-- préférence de langue -->
    <header>
        <a href="auth/login.php">
            <button type="button"><?= $text_translations[$language]['registerLogin'] ?></button>
        </a>
        <a href="auth/register.php">
            <button type="button"><?= $text_translations[$language]['registerSubmit'] ?></button>
        </a>
        <a href="auth/logout.php">
            <button type="button"><?= $text_translations[$language]['registerLogout'] ?></button>
        </a>

        <form method="POST">
            <label for="language">
                <?= $text_translations[$language]['language'] ?? "Langue" ?>
            </label>

            <select name="language" id="language">
                <option value="fr" <?= $language === 'fr' ? 'selected' : '' ?>>FR</option>
                <option value="en" <?= $language === 'en' ? 'selected' : '' ?>>EN</option>
            </select>

            <button type="submit">OK</button>
        </form>

        <form method="POST">
            <button type="submit" name="delete_cookie"><?= $text_translations[$language]['viewDelete'] ?></button>
        </form>
    </header>

    <!-- accueil normal -->
    <main class="container">
        <h1><?= $text_translations[$language]['homeH1'] ?></h1>

        <p><?= $text_translations[$language]['homeText'] ?></p>


        <p><a href="food/index.php"><button><?= $text_translations[$language]['homeButton'] ?></button></a></p>
    </main>
</body>

</html>