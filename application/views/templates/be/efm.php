<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!-- Script of easy file manager -->
<script>
    (function ($) {
        $.fn.tablesorter = function () {
            var $table = this;
            this.find('th:not(#chks)').click(function () {
                var idx = $(this).index();
                var direction = $(this).hasClass('sort_asc');
                $table.tablesortby(idx, direction);
            });
            return this;
        };

        $.fn.tablesortby = function (idx, direction) {
            var $rows = this.find('tbody tr');

            function elementToVal(a) {
                var $a_elem = $(a).find('td:nth-child(' + (idx + 1) + ')');
                var a_val = $a_elem.attr('data-sort') || $a_elem.text().toLowerCase();
                return (a_val == parseInt(a_val) ? parseInt(a_val) : a_val);
            }

            $rows.sort(function (a, b) {
                var a_val = elementToVal(a), b_val = elementToVal(b);
                return (a_val > b_val ? 1 : (a_val == b_val ? 0 : -1)) * (direction ? 1 : -1);
            });
            this.find('th').removeClass('sort_asc sort_desc');
            $(this).find('thead th:nth-child(' + (idx + 1) + ')').addClass(direction ? 'sort_desc' : 'sort_asc');
            for (var i = 0; i < $rows.length; i++) {
                this.append($rows[i]);
            }
            this.settablesortmarkers();
            this.movefoldersbeside();

            return this;
        };

        $.fn.movefoldersbeside = function () {
            var self = this;
            var $rows = self.find('tbody tr');

            $($rows.get().reverse()).each(function () {
                if ($(this).hasClass('is_dir')) {
                    $(this).prependTo(self.find('tbody'));
                }
            });

            return this;
        };

        $.fn.retablesort = function () {
            var $e = this.find('thead th.sort_asc, thead th.sort_desc');
            if ($e.length)
                this.tablesortby($e.index(), $e.hasClass('sort_desc'));

            return this;
        };

        $.fn.settablesortmarkers = function () {
            this.find('thead th span.indicator').remove();
            this.find('thead th.sort_asc').append('<span class="indicator">&darr;<span>');
            this.find('thead th.sort_desc').append('<span class="indicator">&uarr;<span>');
            return this;
        };
    })(jQuery);

    $(function () {
        var
            defaultRout = '<?= base_url(); ?>admin/',
            urlPath = defaultRout + "easyFileManager";

        var chksLen = 0,
            chksPath = [];

        var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)') || 0)[2];
        var MAX_UPLOAD_SIZE = <?= $data['upload']['MAX_UPLOAD_SIZE'] ?>;
        var $tbody = $('#list');
        $(window).on('hashchange', list).trigger('hashchange');
        $('#table').tablesorter();

        $('#table').on('click', '.delete', function (data) {
            var sure = confirm('آیا مطمئن هستید؟');
            if (sure) {
                $.post(urlPath, {'do': 'delete', file: $(this).attr('data-file'), xsrf: XSRF}, function (response) {
                }, 'json');
                list();
            }
            return false;
        });

        $('#mkdir').submit(function (e) {
            var hashval = decodeURIComponent(window.location.hash.substr(1)),
                $dir = $(this).find('#dirname[name="name"]');
            e.preventDefault();
            $dir.val().length && $.post(urlPath, {
                'do': 'mkdir',
                name: $dir.val(),
                xsrf: XSRF,
                file: hashval
            }, function (data) {
            }, 'json');
            $dir.val('');
            list();
            refreshtree();
            return false;
        });

        <?php if($data['upload']['allow_upload']): ?>
        // file upload stuff
        $('#file_drop_target').on('dragover', function () {
            $(this).addClass('drag_over');
            return false;
        }).on('dragend', function () {
            $(this).removeClass('drag_over');
            return false;
        }).on('drop', function (e) {
            e.preventDefault();
            var files = e.originalEvent.dataTransfer.files;
            $.each(files, function (k, file) {
                uploadFile(file);
            });
            $(this).removeClass('drag_over');
        });
        $('#file_drop_target').find('input[type=file]').change(function (e) {
            e.preventDefault();
            $.each(this.files, function (k, file) {
                uploadFile(file);
            });
        });

        function uploadFile(file) {
            var folder = decodeURIComponent(window.location.hash.substr(1));
            if (file.size > MAX_UPLOAD_SIZE) {
                var $error_row = renderFileSizeErrorRow(file, folder);
                $('#upload_progress').append($error_row);
                window.setTimeout(function () {
                    $error_row.fadeOut();
                }, 5000);
                return false;
            }

            var $row = renderFileUploadRow(file, folder);
            $('#upload_progress').append($row);
            var fd = new FormData();
            fd.append('file_data', file);
            fd.append('file', folder);
            fd.append('xsrf', XSRF);
            fd.append('do', 'upload');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', urlPath);
            xhr.onload = function () {
                $row.remove();
                list();
            };
            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    $row.find('.progress').css('width', (e.loaded / e.total * 100 | 0) + '%');
                }
            };
            xhr.send(fd);
        }

        function renderFileUploadRow(file, folder) {
            return $row = $('<div/>')
                .append($('<span class="fileuploadname" />').text((folder ? folder + '/' : '') + file.name))
                .append($('<div class="progress_track"><div class="progress"></div></div>'))
                .append($('<span class="size" />').text(formatFileSize(file.size)))
        }

        function renderFileSizeErrorRow(file, folder) {
            return $row = $('<div class="error" />')
                .append($('<span class="fileuploadname" />').text('Error: ' + (folder ? folder + '/' : '') + file.name))
                .append($('<span/>').html(' file size - <b>' + formatFileSize(file.size) + '</b>'
                    + ' exceeds max upload size of <b>' + formatFileSize(MAX_UPLOAD_SIZE) + '</b>'));
        }
        <?php endif; ?>

        $lazyFirstTime = true;

        function list() {
            var hashval = window.location.hash.substr(1);
            $.get(urlPath, {
                'do': 'list',
                file: hashval
            }, function (data) {
                $tbody.empty();
                $('#breadcrumb').empty().html(renderBreadcrumbs(hashval));
                if (data.success) {
                    $.each(data.results, function (k, v) {
                        $tbody.append(renderFileRow(v));
                    });

                    $threshold = $lazyFirstTime ? 500 : 0;
                    $lazy = $('.lazy').lazy({
                        effect: "fadeIn",
                        effectTime: 800,
                        threshold: $threshold,
                        appendScroll: $('#table').closest('.lazyContainer').length ? $('#table').closest('.lazyContainer') : window,
                        // callback
                        afterLoad: function (element) {
                            $(element).css({'background': 'none'});
                        }
                    });
                    $lazyFirstTime = false;

                    !data.results.length && $tbody.append('<tr><td class="empty" colspan=5>این پوشه خالی می‌باشد</td></tr>');
                    data.is_writable ? $('body').removeClass('no_write') : $('body').addClass('no_write');
                } else {
                    console.warn(data.error.msg);
                }
                $('#table').retablesort();

                $('#table').find('td.first').on('click', function (e) {
                    if ($(this).find('a.name.image').length) {
                        e.preventDefault();
                        $('#table').find('tr').removeClass('selectable');
                        $(this).closest('tr').addClass('selectable');
                    }
                });

                $('#table').find('td.first').off('dblclick').on('dblclick', function () {
                    if($('#file-ok').length) {
                        $('#file-ok').trigger('click');
                    }
                    if($('#choose_file').length) {
                        $('#choose_file').trigger('click');
                    }
                });
            }, 'json');
        }

        function renderFileRow(data) {
            var $checkbox = '';

            var $link = setImagesBg(data);
            var allow_direct_link = <?= $data['upload']['allow_direct_link'] ? 'true' : 'false'; ?>;
            if (!data.is_dir && !allow_direct_link) $link.css('pointer-events', 'none');

            // Download Icon
            var winLoc = window.location.href.split('#')[0];
            var $dl_link = $('<a/>').attr('href', winLoc + '/download/' + data.path.replace('.', '@'))
                .attr('target', "_blank").addClass('download btn btn-success').text('دانلود').prepend("<i class='icon-download4 position-right'></i>");
            var $delete_link = $('<a href="#" />').attr('data-file', data.path).addClass('delete btn btn-default').text('حذف').prepend("<i class='icon-cross3 position-right'></i>");
            var perms = [];
            if (data.is_readable) perms.push('read');
            if (data.is_writable) perms.push('write');
            if (data.is_executable) perms.push('exec');
            return $('<tr />')
                .addClass(data.is_dir ? 'is_dir' : '')
                .append($('<td class="first" />').append($link))
                .append($('<td/>').attr('data-sort', data.is_dir ? -1 : data.size)
                    .html($('<span class="size" />').text(formatFileSize(data.size))))
                .append($('<td/>').attr('data-sort', data.mtime).text(formatTimestamp(data.mtime)))
                .append($('<td/>').text(perms.join('+')))
                .append($('<td/>').append($dl_link).append(data.is_deleteable ? $delete_link : ''));
        }

        function setImagesBg(data) {
            var $link;
            switch (data.ext) {
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'gif':
                    $link = $('<a class="name image" />')
                        .attr('href', data.path).attr('data-url', "<?= base_url(); ?>" + data.path)
                        .append($('<span class="img-name">' + data.name + '</span>'))
                        .append($('<img class="lazy" data-src="<?php echo base_url(); ?>' + data.path + '" alt="' + data.name + '" />'))
                        .append($('<div style="clear: both;"></div>'));
                    break;
                case 'xls':
                    $link = $('<a class="name image xls" />')
                        .attr('href', data.path).attr('data-url', "<?= base_url(); ?>" + data.path)
                        .append($('<span class="img-name">' + data.name + '</span>'))
                        .append($('<img class="lazy" data-src="<?php echo asset_url(); ?>/images/file-icons/Excel.png" alt="' + data.name + '" />'))
                        .append($('<div style="clear: both;"></div>'));
                    break;
                case 'doc':
                case 'docx':
                    $link = $('<a class="name image doc" />')
                        .attr('href', data.path).attr('data-url', "<?= base_url(); ?>" + data.path)
                        .append($('<span class="img-name">' + data.name + '</span>'))
                        .append($('<img class="lazy" data-src="<?php echo asset_url(); ?>/images/file-icons/Word.png" alt="' + data.name + '" />'))
                        .append($('<div style="clear: both;"></div>'));
                    break;
                case 'pdf':
                    $link = $('<a class="name image pdf" />')
                        .attr('href', data.path).attr('data-url', "<?= base_url(); ?>" + data.path)
                        .append($('<span class="img-name">' + data.name + '</span>'))
                        .append($('<img class="lazy" data-src="<?php echo asset_url(); ?>/images/file-icons/PDF.png" alt="' + data.name + '" />'))
                        .append($('<div style="clear: both;"></div>'));
                    break;
                case 'php':
                    $link = $('<a class="name image php" />')
                        .attr('href', data.path).attr('data-url', "<?= base_url(); ?>" + data.path)
                        .append($('<span class="img-name">' + data.name + '</span>'))
                        .append($('<img class="lazy" data-src="<?php echo asset_url(); ?>/images/file-icons/PHP.png" alt="' + data.name + '" />'))
                        .append($('<div style="clear: both;"></div>'));
                    break;
                case 'mp4':
                case 'ogg':
                case 'webm':
                    $link = $('<a class="name image video" />')
                        .attr('href', data.path).attr('data-url', "<?= base_url(); ?>" + data.path)
                        .append($('<span class="img-name">' + data.name + '</span>'))
                        .append($('<img class="lazy" data-src="<?php echo asset_url(); ?>/images/file-icons/Video.png" alt="' + data.name + '" />'))
                        .append($('<div style="clear: both;"></div>'));
                    break;
                default:
                    $link = $('<a class="name" />')
                        .attr('href', data.is_dir ? '#' + data.path : '<?php echo base_url(); ?>' + data.path)
                        .text(data.name);
                    break;
            }
            return $link;
        }

        function renderBreadcrumbs(path) {
            var base = "",
                pathArr = path.split('\\').join('/').split('/'), $html;

            if (pathArr.length == 1) {
                $html = $('<div/>').append($('<a href="#" class="active">Home</a></div>'));
            } else {
                $html = $('<div/>').append($('<a href="#">Home</a></div>'));
            }
            $.each(pathArr, function (k, v) {
                if (v) {
                    if (k > 1) {
                        var v_as_text;
                        if (pathArr.length == (k + 1)) {
                            v_as_text = decodeURIComponent(v);
                            $html.append($('<span/>').text(' ▸ '))
                                .append($('<a class="active" />').attr('href', '#' + base + v).text(v_as_text));
                        } else {
                            v_as_text = decodeURIComponent(v);
                            $html.append($('<span/>').text(' ▸ '))
                                .append($('<a/>').attr('href', '#' + base + v).text(v_as_text));
                        }
                    }
                    base += v + '/';
                }
            });

            $html.find('a[href]').each(function () {
                var $this = $(this), prevTopScroll;
                $this.on('click', function () {
                    prevTopScroll = $('html').scrollTop();
                    setTimeout(function() {
                        $('html').animate({ scrollTop: prevTopScroll }, 200);
                    }, 200);
                });
            });

            return $html;
        }

        function formatTimestamp(unix_timestamp) {
            var m = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var d = new Date(unix_timestamp * 1000);
            return [m[d.getMonth()], ' ', d.getDate(), ', ', d.getFullYear(), " ",
                (d.getHours() % 12 || 12), ":", (d.getMinutes() < 10 ? '0' : '') + d.getMinutes(),
                " ", d.getHours() >= 12 ? 'PM' : 'AM'].join('');
        }

        function formatFileSize(bytes) {
            var s = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
            for (var pos = 0; bytes >= 1000; pos++, bytes /= 1024) ;
            var d = Math.round(bytes * 10);
            return pos ? [parseInt(d / 10), ".", d % 10, " ", s[pos]].join('') : bytes + ' bytes';
        }

        $('#dirsearch').on('input', function () {
            var filter = $.trim($(this).val());
            if (filter != "") {
                $('#table').find('td.first > a.name').each(function (i) {
                    if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                        $(this).closest('tr').stop().fadeOut(150);
                    } else {
                        $(this).closest('tr').stop().fadeIn(150);
                    }
                });
            } else {
                $('#table').find('tr').stop().fadeIn(150);
            }
        });
    })
</script>