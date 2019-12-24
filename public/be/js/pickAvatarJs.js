(function ($) {
    $(function () {
        var imgExts = ['svg'];
        var filesBody = $('#files-body'),
            files = filesBody.find('.user-avatar'),
            avatarsPath = baseUrl + 'public/fe/img/avatars/',
            selectClass = 'selected',
            nameAttr = 'data-name';
        var imageInpPlaceholder = $('.emote-file'),
            imagePlaceholder = $('.emote-image'),
            imageNamePlaceholder = $('.emote-image-name');

        files.on('click', function (e) {
            // Change selected class
            files.removeClass(selectClass);
            $(this).addClass(selectClass);

            if(!$(this).attr(nameAttr) || !imageInpPlaceholder.length || !imagePlaceholder.length || !imageNamePlaceholder.length) {
                e.stopPropagation();
                return;
            }

            // Show selected avatar in placeholder(s)
            imageInpPlaceholder.val($(this).attr(nameAttr));
            imagePlaceholder.attr('src', avatarsPath + $(this).attr(nameAttr));
            imageNamePlaceholder.text($(this).attr(nameAttr));
        });
    });
})(jQuery);