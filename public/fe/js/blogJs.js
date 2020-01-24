(function ($) {
    'use strict';

    $(function () {
        var
            namespace = 'omega',
            omega = $.omega(),
            //-----
            load_more_cmt_btn,
            comments_container,
            //-----
            page = 2,
            //-----
            comment_url = baseUrl + 'blog/ajaxLoadMoreComments';

        function moreCommentBtnClick() {
            var $this;
            load_more_cmt_btn.on('click.' + namespace, function () {
                $this = $(this);
                //-----
                $this.find('i').addClass('la-spin');
                //-----
                var d = {
                    blogId: blog_id,
                    page: page
                };
                //-----
                omega.ajaxRequest({
                    method: 'POST',
                    url: comment_url,
                    data: d,
                    contentType: false,
                    processData: false,
                }, function (response) {
                    // console.log(response);
                    // console.log(JSON.parse(response));
                    var res = JSON.parse(response);
                    if (res.success) {
                        if(res.success.msg) {
                            if (res.success.msg[0]) {
                                comments_container.append(res.success.msg[0]);
                            }
                            if (!res.success.msg[1]) {
                                load_more_cmt_btn.remove();
                            }
                        } else {
                            omega.showMessage('خطا در واکشی اطلاعات!', 'خطا', 'error', omega.messageIcon.danger);
                        }
                    }
                });
            });
        }

        function prepare() {
            load_more_cmt_btn = $('#loadMoreComment');
            comments_container = $('#commentsContainer');
        }

        prepare();
        moreCommentBtnClick();
    });
})(jQuery);