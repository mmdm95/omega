(function ($) {
    'use strict';

    $(function () {
        var hash = window.location.hash.substr(1);
        var tabs = ['listings', 'profile', 'setting',];

        if ($.inArray(hash, tabs) !== -1) {
            $('a[href="#' + hash + '"]').tab('show');
        }
    });
})(jQuery);