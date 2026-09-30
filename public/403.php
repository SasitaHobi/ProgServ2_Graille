<?php

// Démarre la session
session_start();

// liens
require __DIR__ . '/../src/utils/autoloader.php';
require_once __DIR__ . '/assets/translations.php';
require_once __DIR__ . '/assets/language.php';

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: /auth/login.php');
    exit();
}

// Refuser l'accès et afficher un message d'erreur avec un code 403 Forbidden
http_response_code(403);
?>


<!DOCTYPE html>
<html lang="fr">

<?php
// Affiche le head commun avec le titre de la page 403.
render('head', [
    'title' => $text_translations[$language]['403Title'],
]);
?>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['403H1'] ?></h1>

        <p><?= $text_translations[$language]['403Text'] ?></p>

        <p><a href="index.php"><?= $text_translations[$language]['403Back'] ?></a></p>
    </main>
</body>

</html>