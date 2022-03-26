<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark shadow d-print-none">

    <div class="container-fluid">

        <a class="navbar-brand" href="<?= url() ?>">
            <i class="fas fa-scroll" aria-hidden="true"></i> <?= env("APP_TITLE") ?>
        </a>

        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Ouvrir/fermer le menu de navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto flex-wrap">
                <li class="nav-item <?= activeLink('home') ?>" data-test="link-accueil">
                    <a class="nav-link" href="<?= url() ?>">
                        Accueil
                    </a>
                </li>
            </ul>

            <div class="flex-shrink-0 d-flex align-items-center">

                <!-- Recherche -->

                <form class="form-horizontal form-recherche mb-0" method="POST" action="<?= url() ?>/recherche" role="form">
                    <div class="nav-search mx-lg-3">
                        <label for="recherche" class="sr-only form-label">Recherche</label>
                        <div class="input-group">
                            <input type="search" class="form-control form-control-sm" minlength="3" id="search" name="search" placeholder="Rechercher&hellip;" aria-label="Recherche" />
                            <button type="submit" class="btn btn-dark" aria-label="Rechercher">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

</nav>