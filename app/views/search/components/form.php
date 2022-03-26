<form class="form-horizontal form-recherche" method="POST" action="<?= url() ?>/recherche" role="form">
    <div class="d-flex align-items-end">
        <div class="form-group flex-grow-1 me-2">
            <label class="form-label" for="search"><?= alias("recherche", "recherche") ?></label>
            <input class="form-control" type="search" value="<?= isset($search) ? $search : "" ?>" minlength="3" id="search" name="search" placeholder="<?= alias('recherche', 'placeholder') ?>" aria-label="Recherche" />
        </div>
        <?php if (count($categories) > 1) : ?>
            <div class="form-group me-2">
                <label class="form-label" for="category"><?= alias("recherche", "dans") ?></label>
                <select class="form-select" id="category" name="category">
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat ?>" <?= $cat == $category ? 'selected="true"' : "" ?>><?= alias('recherche', $cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif ?>
        <button type="submit" class="btn btn-secondary" style="height: 38px;" title="<?= alias("recherche", "rechercher") ?>" aria-label="Rechercher">
            <i class="fas fa-search me-2" aria-hidden="true"></i> <?= alias("recherche", "rechercher") ?>
        </button>
    </div>
</form>