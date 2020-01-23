(function ($) {
    'use strict';

    $(function () {
        var
            namespace = 'omega',
            omega = $.omega(),
            //-----
            load_more_cmt_btn;

        function moreCommentBtnClick() {
            load_more_cmt_btn.on('click.' + namespace, function () {

            });
        }

        function prepare() {
            load_more_cmt_btn = $('#loadMoreComment');
        }

        prepare();
    });
})(jQuery);