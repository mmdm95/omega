if (typeof jQuery == 'undefined') {
    throw new Error('jQuery is not added to your project yet!');
} else {
    jQuery(function () {
        (function ($) {
            var imgExts = ['png', 'jpg', 'jpeg', 'gif'];
            var videoExts = ['mp4', 'ogg', 'webm'];

            $.get_base_name = function (fileName) {
                return fileName.split('\\').join('/').split('/').pop();
            };

            $.get_extension = function (fileName) {
                return fileName.split('.').pop();
            };

            function delete_slide_img(selector, e) {
                e.stopPropagation();

                var container = $('.slide-items');
                $(selector).closest('.slide-item').hide(400, function () {
                    $(this).remove();

                    container.find('.slide-item').each(function (i) {
                        $(this).find('.io-image-lbl').text('انتخاب تصویر ' + (i + 1));
                    });
                });
            }

            $('.delete-new-image').on('click', function (e) {
                delete_slide_img(this, e);
            });

            $('.add-slide-image').on('click', function (e) {
                e.stopPropagation();

                var container = $('.slide-items');
                var item = container.find('.slide-item').first().clone(true);

                var deleteBtn = $('<small class="delete-new-image btn btn-danger" title="حذف">&times;</small>');
                var baseUrl = $(this).parent().find('img').attr('data-base-url');

                item.find('.clear-img-val').remove();
                item.find('input').val('');
                item.find('img').attr('src', baseUrl + 'public/images/placeholder.jpg');
                item.find('a.io-image-name').text('');

                deleteBtn.on('click', function (e) {
                    delete_slide_img(this, e);
                });
                deleteBtn.insertAfter(item.find('> div .media-body'));

                container.append(item);

                container.find('.slide-item').each(function (i) {
                    $(this).find('.io-image-lbl').text('انتخاب تصویر ' + (i + 1));
                });
            });

            $('.pick-file').on('click', function () {
                var self = $(this);

                var modalOkClick = function (e) {
                    var clickedFile = $('#modal_full').find('#table tr.selectable td.first a.name.image');

                    if (clickedFile.length != 0) {
                        var clickedFileBaseName = $.get_base_name($(clickedFile).attr('href')),
                            clickedFileExt = $.get_extension($(clickedFile).attr('href'));

                        if (clickedFile.length !== 0) {
                            if ($.inArray(clickedFileExt, imgExts) != -1) {
                                $(self).find('.media-body a.io-image-name').text(clickedFileBaseName);
                                $(self).find('.media a img').attr('src',
                                    $(self).find('.media a img').attr('data-base-url') + $(clickedFile).attr('href'));
                                $(self).find('.image-file').val($(clickedFile).attr('href'));
                            } else {
                                e.stopPropagation();
                            }
                        } else {
                            e.stopPropagation();
                        }
                    } else {
                        e.stopPropagation();
                    }
                };

                $('#file-ok').off('click').on('click', function (e) {
                    modalOkClick(e);
                });
            });

            $('.pick-file-video').on('click', function () {
                var self = $(this);

                var modalOkClick = function (e) {
                    var clickedFile = $('#modal_full').find('#table tr.selectable td.first a.name.image.xls');

                    if (clickedFile.length != 0) {
                        var clickedFileBaseName = $.get_base_name($(clickedFile).attr('href')),
                            clickedFileExt = $.get_extension($(clickedFile).attr('href'));

                        if (clickedFile.length !== 0) {
                            if ($.inArray(clickedFileExt, videoExts) != -1) {
                                $(self).find('.media-body a.io-image-name').text(clickedFileBaseName);
                                $(self).find('.image-file').val($(clickedFile).attr('href'));
                            } else {
                                e.stopPropagation();
                            }
                        } else {
                            e.stopPropagation();
                        }
                    } else {
                        e.stopPropagation();
                    }
                };

                $('#file-ok').off('click').on('click', function (e) {
                    modalOkClick(e);
                });
            });

            $('.clear-img-val').on('click', function (e) {
                e.stopPropagation();
                $(this).parent().parent().find('input').val('').end().parent()
                    .find('img').attr('src', $(this).parent().find('img').attr('data-base-url') + 'public/images/placeholder.jpg');
                $(this).parent().find('a.io-image-name').text('');
            });
            $('.clear-video-val').on('click', function (e) {
                e.stopPropagation();
                $(this).parent().parent().find('input').val('').end().parent()
                    .find('img').attr('src', $(this).parent().find('img').attr('data-base-url') + 'public/images/video-placeholder.png');
                $(this).parent().find('a.io-image-name').text('');
            });
        })(jQuery);
    });
}