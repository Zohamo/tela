<footer class="main-footer d-print-none bg-dark text-light">
    <div class="container-fluid">

        <div class="text-center text-md-start my-2">
            <strong><?= env("APP_TITLE") ?></strong><br />
            <small>
                Version <?= config("version") ?>
                <?php if (!in_array(env("ENV_NAME"), ['integration', 'production'])) : ?>
                    | <span class="text-uppercase"><?= env("ENV_NAME") ?></span>
                <?php endif ?>
            </small>
        </div>

    </div>
</footer>