/* ------------------------------------------------------------------------------
 *
 *  # Media gallery
 *
 *  Specific JS code additions for Gallery pages
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function () {

    if ($('[data-popup="lightbox"]').length) {
        // Initialize lightbox
        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });
    }

});
