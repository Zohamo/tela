<div class="container">

    <h1 class="page-title">
        <i class="fas fa-search" aria-hidden="true"></i> <?= $title ?>
    </h1>

    <!-- FORMULAIRE DE RECHERCHE -->

    <section class="form-recherche-multi border-bottom pb-4">

        <?php include "components/form.php" ?>

    </section>

    <?php if ($search) : ?>

        <!-- RESULTATS: header -->

        <section class="recherche-rappel d-md-flex justify-content-between align-items-center my-3">
            <p class="my-3">
                <span class="me-3">Résultats de votre recherche&nbsp;:</span>
                <span class="alert alert-primary h5 p-2">“<?= $search ?>”</span>
            </p>
        </section>

        <!-- RESULTATS: content -->

        <section class="recherche-resultats">

            <?php if ($results) : ?>

                <?php foreach ($results as $categoryName => $categoryResults) : ?>

                    <?php if (!empty($categoryResults)) : $displayResults = true; ?>

                        <?php if (is_readable(path('views') . "/recherche/components/results-$categoryName.php")) : ?>

                            <?php include "components/results-$categoryName.php" ?>

                        <?php else : ?>

                            <?php include "components/results.php" ?>

                        <?php endif ?>

                    <?php endif ?>

                <?php endforeach ?>

            <?php endif ?>

            <?php if (!$results || empty($displayResults)) : ?>

                <h3 class="text-center"><em>Aucun résultat n'a été trouvé.</em></h3>

            <?php endif ?>

        </section>

    <?php endif ?>

</div>