<div id="<?= $modalId ?>" class="modal-confirm-delete modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer" />
            </div>
            <div class="modal-body">
                <p class="mb-0"><?= $message ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal" data-test="cancel">
                    <i class="fas fa-times me-2" aria-hidden="true"></i> Annuler
                </button>
                <a href="<?= $url ?>" class="btn btn-confirm btn-danger" data-test="confirm">
                    <i class="fas fa-trash me-2" aria-hidden="true"></i> Supprimer
                </a>
            </div>
        </div>
    </div>
</div>