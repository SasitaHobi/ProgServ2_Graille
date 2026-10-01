<?php

// Démarrer la session
session_start();

// Constantes et liens
require __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../assets/translations.php';
require_once __DIR__ . '/../assets/language.php';


// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: login.php');
    exit();
}

// Détruit la session
session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html lang="fr">

<?php
// Affiche le head commun avec le titre de la page de déconnexion.
render('head', [
    'title' => $text_translations[$language]['logoutTitle'],
]);
?>

<body>
    <main class="container">
        <h1><?= $text_translations[$language]['logoutH1'] ?></h1>

        <p><?= $text_translations[$language]['logoutText'] ?></p>

        <a href="../index.php">
            <button type="button"><?= $text_translations[$language]['logoutBack'] ?></button>
        </a>

        <a href="login.php">
            <button type="button"><?= $text_translations[$language]['registerLogin'] ?></button>
        </a>

    </main>
    <?php
    // Affiche le footer commun de la plateforme.
    render('footer');
    ?>
</body>

</html>