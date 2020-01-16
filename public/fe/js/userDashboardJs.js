(function ($) {
    'use strict';

    $(function () {
        var
            inp_file,
            img_placeholder_class = '.user-profile-image',
            img_placeholder,
            //-----
            upload_url = baseUrl + 'uploadUserImage';

        function prepare() {
            img_placeholder = $(img_placeholder_class);

        }

        prepare();
    });

    $(function () {
        var hash = window.location.hash.substr(1);
        var tabs = ['listings', 'profile', 'setting',];

        if ($.inArray(hash, tabs) !== -1) {
            $('a[href="#' + hash + '"]').tab('show');
        }
    });
})(jQuery);