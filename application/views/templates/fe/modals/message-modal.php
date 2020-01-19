<div class="modal fade <?= $class ?? ''; ?>" id="<?= $id ?? ''; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-top-40 p-bottom-50">
                <span class="font-size-huge <?= $icon ?? ''; ?>"></span>
                <h1 class="display-3 m-bottom-10 iranyekan-regular"><?= $title ?? ''; ?></h1>
                <p class="m-bottom-30"><?= $message ?? ''; ?></p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary m-right-15" <?= isset($cancelId) ? 'id="' . $cancelId . '"' : ''; ?>
                            data-dismiss="modal"><?= $cancelMessage ?? 'لغو'; ?></button>
                </div>
            </div>
        </div>
    </div>
</div>