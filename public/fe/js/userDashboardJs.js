(function ($) {
    'use strict';

    var
        namespace = 'omega';
    $(function () {
        var
            omega = $.omega(),
            //-----
            inp_file,
            img_placeholder_class = '.user-profile-image',
            img_placeholder,
            //-----
            upload_url = baseUrl + 'user/ajaxUploadUserImage';

        function inpFileUploadChange() {
            var $this, fd;
            inp_file.on('change.' + namespace, function () {
                if (imageChecker()) {
                    $this = $(this);
                    //-----
                    fd = new FormData();
                    fd.append('file', $this.get(0).files[0]);
                    //-----
                    omega.question('تغییر تصویر پروفایل؟', function () {
                        omega.ajaxRequest({
                            method: 'POST',
                            url: upload_url,
                            data: fd,
                            contentType: false,
                            processData: false,
                        }, function (response) {
                            // console.log(response);
                            // console.log(JSON.parse(response));
                            var res = JSON.parse(response);
                            omega.processAjaxData(res, function (content) {
                                if (res.success) {
                                    if (content && content.length) {
                                        img_placeholder.attr('src', baseUrl + content[0] + '?' + Date.now());
                                    }
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
        var inp_action_toggle = $('.action-toggle');
        inp_action_toggle.each(function () {
            var $this, at_el, at, action;
            //-----
            $this = $(this);
            at_el = $this.data('action-toggle-el');
            at = $this.data('action-toggle');
            action = $this.data('action');
            //-----
            $this.on('change.' + namespace, function () {
                if ($(this).is(':checked')) {
                    if (typeof at !== typeof undefined) {
                        switch (action.toLowerCase()) {
                            case 'enable':
                                if (true == at) {
                                    $(at_el).attr('disabled', false);
                                } else {
                                    $(at_el).attr('disabled', true);
                                }
                                break;
                        }
                    }
                }
            }).trigger('change.' + namespace);
        });
    });

    $(function () {
        var hash = window.location.hash.substr(1);
        var tabs = ['listings', 'profile', 'setting'];

        if ($.inArray(hash, tabs) !== -1) {
            $('a[href="#' + hash + '"]').tab('show');
        }
    });
})(jQuery);