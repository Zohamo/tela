<div class="container-fluid">

    <h1 class="page-title">
        <?= title($title) ?>
    </h1>

    <div class="table-responsive my-3">
        <table class="data-table table table-sm table-bordered table-striped align-middle" aria-label="Liste des <?= $title ?>">
            <thead>
                <tr>
                    <?php $className = \App\Functions\ClassUtils::getName($list[0], false); ?>
                    <?php foreach (array_keys($list[0]->properties()) as $attrName) : ?>
                        <th scope="col"><?= alias($className, $attrName) ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $item) : ?>
                    <tr>
                        <?php foreach (array_keys($list[0]->properties()) as $attrName) : ?>
                            <td><?= $item->$attrName ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>