<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<script type="application/javascript">
    (function ($) {
        'use strict';
        $(function () {
            function myFileBrowser(callback, value, meta) {
                var cmsURL = "<?= str_replace('\\', '/', base_url()); ?>admin/browser";    // script URL - use an absolute path!
                cmsURL = cmsURL + "/type=" + meta.filetype;

                tinymce.activeEditor.windowManager.open({
                    title: 'File Manager',
                    url: cmsURL,
                    width: 600,
                    height: 600
                }, {
                    onInsert: function (url) {
                        callback(url);
                    }
                });
            }

            tinyMCE.init({
                selector: '#cntEditor',
                height: 500,
                theme: 'modern',
                plugins: [
                    'emoticons template paste textcolor textpattern imagetools',
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality'
                ],
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor",
                // textcolor_map: [
                //     "000000", "Black",
                //     "993300", "Burnt orange",
                //     "333300", "Dark olive",
                //     "003300", "Dark green",
                //     "003366", "Dark azure",
                //     "000080", "Navy Blue",
                //     "333399", "Indigo",
                //     "333333", "Very dark gray",
                //     "800000", "Maroon",
                //     "FF6600", "Orange",
                //     "808000", "Olive",
                //     "008000", "Green",
                //     "008080", "Teal",
                //     "0000FF", "Blue",
                //     "666699", "Grayish blue",
                //     "808080", "Gray",
                //     "FF0000", "Red",
                //     "FF9900", "Amber",
                //     "99CC00", "Yellow green",
                //     "339966", "Sea green",
                //     "33CCCC", "Turquoise",
                //     "3366FF", "Royal blue",
                //     "800080", "Purple",
                //     "999999", "Medium gray",
                //     "FF00FF", "Magenta",
                //     "FFCC00", "Gold",
                //     "FFFF00", "Yellow",
                //     "00FF00", "Lime",
                //     "00FFFF", "Aqua",
                //     "00CCFF", "Sky blue",
                //     "993366", "Red violet",
                //     "FFFFFF", "White",
                //     "FF99CC", "Pink",
                //     "FFCC99", "Peach",
                //     "FFFF99", "Light yellow",
                //     "CCFFCC", "Pale green",
                //     "CCFFFF", "Pale cyan",
                //     "99CCFF", "Light sky blue",
                //     "CC99FF", "Plum"
                // ],
                image_advtab: true,
                file_picker_callback: function (callback, value, meta) {
                    myFileBrowser(callback, value, meta);
                }
            });

            tinyMCE.init({
                selector: '.cntEditor',
                height: 500,
                theme: 'modern',
                plugins: [
                    "textcolor",
                    'emoticons template paste textpattern imagetools',
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality'
                ],
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor",
                image_advtab: true,
                file_picker_callback: function (callback, value, meta) {
                    myFileBrowser(callback, value, meta);
                }
            });
        });
    })(jQuery);
</script>