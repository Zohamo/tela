<div class="container p-5">

    <h1>Erreur serveur</h1>
    <p>Une erreur s'est produite, veuillez contacter votre administrateur si le problème persiste.</p>
    <?php if (!empty($message)) : ?>
        <p class="text-muted"><?= $message ?></p>
    <?php endif ?>
</div>