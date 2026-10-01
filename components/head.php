<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if ($colorScheme ?? false) { ?>
        <meta name="color-scheme" content="light dark">
    <?php } ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <link rel="stylesheet" href="/assets/custom.css">
   
    <!--
        Le titre est transmis par la page avec render().
        Si aucun titre n'est transmis, "Graille" est utilisé par défaut.
    -->
    <title><?= htmlspecialchars($title ?? 'Graille') ?></title>
</head>