<div class="card shadow mb-3">
    <div class="card-header bg-secondary text-white">
        <h3 class="card-title mb-0">Utilisateurs <small><em>(<?= count($categoryResults) ?>)</em></small></h3>
    </div>

    <div class="card-body py-0">
        <table class="table table-sm table-hover">
            <caption class="sr-only">Liste des utilisateurs trouvés lors de la recherche</caption>
            <thead>
                <?php foreach (['uti_prenom', 'uti_nom', 'uti_matricule'] as $col) : ?>
                    <th scope="col"><?= alias('utilisateur', $col) ?></th>
                <?php endforeach ?>
            </thead>
            <tbody>
                <?php foreach ($categoryResults as $result) : ?>
                    <tr>
                        <td><?= $result->uti_prenom ?></td>
                        <td class="text-uppercase"><?= $result->uti_nom ?></td>
                        <td class="font-italic"><?= $result->uti_matricule ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>