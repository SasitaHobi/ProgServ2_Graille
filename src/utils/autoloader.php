<?php
// Charge les classes automatiquement
spl_autoload_register(function ($class) {
    // Convertit les séparateurs de namespace en séparateurs de répertoires
    $relativePath = str_replace('\\', '/', $class);

    // Construit le chemin complet du fichier
    $file = __DIR__ . '/../classes/' . $relativePath . '.php';

    // Vérifie si le fichier existe avant de l'inclure
    if (file_exists($file)) {
        // Inclut le fichier de classe
        require_once $file;
    }
});


//Affiche un composant d'interface enli passant éventuellement des données
function render(string $component, array $data = []): void
{
    $componentPath = __DIR__ . '/../../components/' . $component . '.php';

    extract($data, EXTR_SKIP);

    require $componentPath;
}