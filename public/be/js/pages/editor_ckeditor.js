/* ------------------------------------------------------------------------------
 *
 *  # CKEditor editor
 *
 *  Specific JS code additions for editor_ckeditor.html page
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function () {
    var
        domain_name = window.location.pathname.split('/'),
        default_rout = '/' + domain_name[1] + '/admin/',
        baseUrl = $('#base').val();

    var CkEditorImageBrowser = {};

    CkEditorImageBrowser.ImgExts = ['png', 'jpg', 'jpeg', 'gif'];
    CkEditorImageBrowser.VideoExts = ['mp4', 'ogg', 'webm'];

    CkEditorImageBrowser.get_base_name = function (fileName) {
        return fileName.split('\\').join('/').split('/').pop();
    };

    CkEditorImageBrowser.get_extension = function (fileName) {
        return fileName.split('.').pop();
    };

    CkEditorImageBrowser.ckFunctionNum = null;

    CkEditorImageBrowser.init = function () {
        CkEditorImageBrowser.ckFunctionNum = CkEditorImageBrowser.getQueryStringParam('CKEditorFuncNum');
        CkEditorImageBrowser.initEventHandlers();
    };

    CkEditorImageBrowser.initEventHandlers = function () {
        $(document).on('dblclick', '#table td.first', function (e) {
            var href = CkEditorImageBrowser.ImageOkClick(e);
            window.opener.CKEDITOR.tools.callFunction(CkEditorImageBrowser.ckFunctionNum, baseUrl + '/' + href);
            window.close();
        });
    };

    CkEditorImageBrowser.ImageOkClick = function (e) {
        var clickedFile = $('#table').find('tr.selectable td.first a.name.image');
        var href = null;

        if (clickedFile.length != 0) {
            var clickedFileBaseName = CkEditorImageBrowser.get_base_name($(clickedFile).attr('href')),
                clickedFileExt = CkEditorImageBrowser.get_extension($(clickedFile).attr('href'));

            if (clickedFile.length !== 0) {
                if ($.inArray(clickedFileExt, CkEditorImageBrowser.ImgExts) != -1) {
                    $(self).find('.media-body a.io-image-name').text(clickedFileBaseName);
                    $(self).find('.media a img').attr('src',
                        $(self).find('.media a img').attr('data-base-url') + $(clickedFile).attr('href'));
                    $(self).find('.image-file').val($(clickedFile).attr('href'));

                    href = $(clickedFile).attr('href');
                } else {
                    e.stopPropagation();
                }
            } else {
                e.stopPropagation();
            }
        } else {
            e.stopPropagation();
        }

        return href;
    };

    CkEditorImageBrowser.getQueryStringParam = function (name) {
        var regex = new RegExp('[?&]' + name + '=([^&]*)'),
            result = window.location.search.match(regex);

        return (result && result.length > 1 ? decodeURIComponent(result[1]) : null);
    };

    CkEditorImageBrowser.init();

    CKEDITOR.plugins.add('imagebrowser', {
        "init": function (editor) {
            editor.config.filebrowserImageBrowseUrl = default_rout + "browser.php";
        }
    });

    if ($('#browse-editor-full').length) {
        // Full featured editor
        CKEDITOR.replace('browse-editor-full', {
            "extraPlugins": 'imagebrowser',
            "imageBrowser_listUrl": "/ckeditor-imagebrowser/demo/images/images_list.json",
            height: '400px',
            language: 'fa',
            toolbar: [
                ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Print', 'SpellChecker', 'Scayt'],
                ['Link', 'Unlink', 'Anchor'],
                ['Flash', 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],
                ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv'],
                '/',
                ['Undo', 'Redo', '-', 'Find', 'Replace'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['Styles', 'Format', 'Font', 'FontSize'],
                ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
                ['Maximize']
            ]
        });
    }

    if ($('#editor-full').length) {
        // Full featured editor
        CKEDITOR.replace('editor-full', {
            height: '400px',
            language: 'fa',
            toolbar: [
                ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Print', 'SpellChecker', 'Scayt'],
                ['Link', 'Unlink', 'Anchor'],
                ['Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],
                ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv'],
                '/',
                ['Undo', 'Redo', '-', 'Find', 'Replace'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['Styles', 'Format', 'Font', 'FontSize'],
                ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
                ['Maximize']
            ]
        });
    }


// Readonly editor
// ------------------------------

// Setup
//     var editor = CKEDITOR.replace('editor-readonly', {
//         height: '400px'
//     });

// The instanceReady event is fired, when an instance of CKEditor has finished its initialization.
//     CKEDITOR.on('instanceReady', function (ev) {

    // Show this "on" button.
    // document.getElementById('readOnlyOn').style.display = '';

    // Event fired when the readOnly property changes.
    // editor.on('readOnly', function () {
    //     document.getElementById('readOnlyOn').style.display = this.readOnly ? 'none' : '';
    //     document.getElementById('readOnlyOff').style.display = this.readOnly ? '' : 'none';
    // });
    // });

// Toggle state
//     function toggleReadOnly(isReadOnly) {
//         editor.setReadOnly(isReadOnly);
//     }

    // document.getElementById('readOnlyOn').onclick = function () {
    //     toggleReadOnly()
    // };
    // document.getElementById('readOnlyOff').onclick = function () {
    //     toggleReadOnly(false)
    // };


// Enter key configuration
// ------------------------------

// Define editor
//     var editor2;

// Setup editor
//     function changeEnter() {
    // If we already have an editor, let's destroy it first.
    // if (editor2)
    //     editor2.destroy(true);

    // Create the editor again, with the appropriate settings.
    // editor2 = CKEDITOR.replace('editor-enter', {
    //     height: '400px',
    //     extraPlugins: 'enterkey',
    //     enterMode: Number(document.getElementById('xEnter').value),
    //     shiftEnterMode: Number(document.getElementById('xShiftEnter').value)
    // });
    // }

// Run on indow load
//     window.onload = changeEnter;

// Change configuration
//     document.getElementById('xEnter').onchange = function () {
//         changeEnter()
//     };
//     document.getElementById('xShiftEnter').onchange = function () {
//         changeEnter()
//     };

// We are using Select2 selects here
//     $('.select').select2({
//         minimumResultsForSearch: Infinity
//     });


// Inline editor
// ------------------------------

// We need to turn off the automatic editor creation first
    CKEDITOR.disableAutoInline = true;

// Attach editor to the area
//     var editor3 = CKEDITOR.inline('editor-inline');

})
;
