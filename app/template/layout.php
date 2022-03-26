<!DOCTYPE html>
<html lang="fr">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title><?= title($title, 'head') ?></title>
    <link rel="icon" href="<?= url('public') ?>/favicon.svg" />

    <?php scripts('head', $scripts) ?>

</head>

<body>

    <?php include path('template') . "/components/header.php"; ?>

    <?php if ($alerts) : ?>
        <?php include path('template') . "/components/alerts.php" ?>
    <?php endif ?>

    <main class="pb-5">

        <?php include $view; ?>

    </main>

    <?php include path('template') . "/components/footer.php"; ?>

    <?php include path('template') . "/components/json-data.php"; ?>

    <?php scripts('end', $scripts) ?>

</body>

</html>