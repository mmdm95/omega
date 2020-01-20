if (typeof jQuery == 'undefined') {
    throw new Error('jQuery is not added to your project yet!');
} else {
    jQuery(function () {
        (function ($) {
            var imgExts = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
            var videoExts = ['mp4', 'ogg', 'webm'];

            $.get_base_name = function (fileName) {
                return fileName.split('\\').join('/').split('/').pop();
            };

            $.get_extension = function (fileName) {
                return fileName.split('.').pop();
            };

            function delete_slide_img(selector, e) {
                e.stopPropagation();

                var container = $(selector).closest('.slide-items');
                $(selector).closest('.slide-item').fadeOut(400, function () {
                    $(this).remove();

                    var is_video = container.find('.pick-file-video').length;
                    var txt = is_video ? 'انتخاب ویدیو ' : 'انتخاب تصویر ';
                    container.find('.slide-item').each(function (i) {
                        $(this).find('.io-image-lbl').text(txt + (i + 1));
                    });
                });
            }

            $('.delete-new-image').off('click').on('click', function (e) {
                delete_slide_img(this, e);
            });

            $('.add-slide-image').off('click').on('click', function (e) {
                e.stopPropagation();

                var container = $(this).closest('.slide-items');
                var item = container.find('.slide-item').first().clone(true);

                var deleteBtn = $('<small class="delete-new-image btn btn-danger" title="حذف">&times;</small>');

                var is_video = container.find('.pick-file-video').length;

                item.find('.clear-img-val').remove();
                item.find('.clear-video-val').remove();
                item.find('input').val('');
                item.find('img').attr('src', baseUrl + (is_video ? 'public/be/images/video-placeholder.png' : 'public/be/images/placeholder.jpg'));
                item.find('a.io-image-name').text('');

                deleteBtn.off('click').on('click', function (e) {
                    delete_slide_img(this, e);
                });
                deleteBtn.insertAfter(item.find('> div .media-body'));

                container.append(item);

                var txt = is_video ? 'انتخاب ویدیو ' : 'انتخاب تصویر ';
                container.find('.slide-item').each(function (i) {
                    $(this).find('.io-image-lbl').text(txt + (i + 1));
                });
            });

            $('.pick-file').off('click').on('click', function () {
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
                                    baseUrl + $(clickedFile).attr('href'));
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

            $('.pick-file-video').off('click').on('click', function () {
                var self = $(this);

                var modalOkClick = function (e) {
                    var clickedFile = $('#modal_full').find('#table tr.selectable td.first a.name.image.video');

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

            $('.clear-img-val').off('click').on('click', function (e) {
                e.stopPropagation();
                $(this).parent().parent().find('input').val('').end().parent()
                    .find('img').attr('src', baseUrl + 'public/be/images/placeholder.jpg');
                $(this).parent().find('a.io-image-name').text('');
            });
            $('.clear-video-val').off('click').on('click', function (e) {
                e.stopPropagation();
                $(this).parent().parent().find('input').val('').end().parent()
                    .find('img').attr('src', baseUrl + 'public/be/images/video-placeholder.png');
                $(this).parent().find('a.io-image-name').text('');
            });
        })(jQuery);
    });
}