<div class="card text-white bg-dark m-4 d-print-none">
    <div class="card-body d-flex">

        <i class="fas fa-bug fa-4x mt-4" aria-hidden="true"></i>

        <div class="ms-4">

            <h5 class="card-title"><i class="fas fa-arrow-right me-2" aria-hidden="true"></i> Requête</h5>

            <?php if ($className) : ?>
                <div class="d-flex align-items-center mb-2">
                    <h6 class="card-subtitle text-muted">Classe à instancier&nbsp;:</h6>
                    <p class="card-text ms-2"><code class="text-white"><?= $className ?></code></p>
                </div>
            <?php endif ?>

            <div class="d-flex align-items-center">
                <h6 class="card-subtitle text-muted">Requête SQL&nbsp;:</h6>
                <p class="card-text ms-2"><code class="text-white"><?= $query ?></code></p>
            </div>

            <?php if (!empty($values)) : ?>
                <div class="d-flex align-items-start mt-2">
                    <h6 class="card-subtitle text-muted me-2">Valeurs&nbsp;:</h6>
                    <pre class="bg-light px-2"><?php var_dump($values) ?></pre>
                </div>
            <?php endif ?>