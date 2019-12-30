<!-- Standard width modal -->
<div id="modal_full" class="modal fade lazyContainer">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">
                    انتخاب فایل
                </h5>
            </div>

            <div id="files-body" class="modal-body">
                <?php $this->view("templates/be/efm-view", $data); ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="icon-cancel-circle2 position-left" aria-hidden="true"></i>
                    لغو
                </button>
                <button id="file-ok" type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="icon-checkmark-circle position-left" aria-hidden="true"></i>
                    انتخاب
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /standard width modal -->