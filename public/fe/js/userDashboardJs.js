(function ($) {
    'use strict';

    $(function () {
        var
            namespace = 'omega',
            omega = $.omega(),
            //-----
            inp_file,
            img_placeholder_class = '.user-profile-image',
            img_placeholder,
            //-----
            upload_url = baseUrl + 'ajaxUploadUserImage';

        function inpFileUploadChange() {
            var $this, data;
            inp_file.on('change.' + namespace, function () {
                if (imageChecker()) {
                    $this = $(this);
                    //-----
                    data = new FormData();
                    data.append('file', $this.get(0).files[0]);
                    //-----
                    omega.question('تغییر تصویر پروفایل؟', function () {
                        omega.ajaxRequest({
                            method: 'POST',
                            url: upload_url,
                            data: data,
                            enctype: 'multipart/form-data',
                            cache: false,
                            contentType: false,
                            processData: false,
                        }, function (response) {
                            // console.log(response);
                            // console.log(JSON.parse(response));
                            var res = JSON.parse(response);
                            omega.processAjaxData(res, function (content) {
                                if (res.success) {
                                    img_placeholder.attr('src', content);
                                }
                            });
                        });
                    });
                }
            });
        }

        function imageChecker() {
            var img_name, size, ext;
            //-----
            img_name = inp_file.first().val();
            size = inp_file.get(0).files[0].size;
            //-----
            ext = img_name.substr((img_name.lastIndexOf('.') + 1)).toLowerCase();
            if ('jpg' == ext || 'jpeg' == ext || 'png' == ext || 'gif' == ext) {
                if (size <= 4000000) { // 4MB image
                    return true;
                } else {
                    omega.showMessage('حجم فایل عکس باید کمتر از ۴ مگابایت باشد.', 'اخطار', 'warning', omega.messageIcon.warning);
                }
            } else {
                omega.showMessage('فایل باید از نوع تصویر باشد.', 'اخطار', 'warning', omega.messageIcon.warning);
            }
            return false;
        }

        function prepare() {
            img_placeholder = $(img_placeholder_class);
            //-----
            inp_file = $('#customFile');
        }

        prepare();
        inpFileUploadChange();
    });

    $(function () {
        var hash = window.location.hash.substr(1);
        var tabs = ['listings', 'profile', 'setting',];

        if ($.inArray(hash, tabs) !== -1) {
            $('a[href="#' + hash + '"]').tab('show');
        }
    });
})(jQuery);