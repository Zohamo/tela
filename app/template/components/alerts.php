<div class="container main-alerts d-print-none">
    <?php foreach ($alerts as $alert) : ?>
        <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show mt-3" role="alert">
            <?= $alert['message'] ?>
            <button type="button" class="btn-close" data-test="close" data-bs-dismiss="alert" aria-label="Fermer" />
        </div>
    <?php endforeach; ?>
</div>