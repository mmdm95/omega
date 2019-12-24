$(function () {

    // Defaults
    Dropzone.autoDiscover = false;

    var
        domain_name = window.location.pathname.split('/'),
        default_rout = '/' + domain_name[1] + '/isoadmin/',
        url_path = default_rout + "uploadfile",
        url_path_del = default_rout + "uploadfiledelete";

    // Removable thumbnails
    new Dropzone("#io-upload-form-input", {
        paramName: "file", // The name that will be used to transfer the file
        dictDefaultMessage: 'Drop files to upload <span>or CLICK</span>',
        maxFilesize: 5, // MB
        acceptedFiles: 'image/*,.xls',
        addRemoveLinks: true,
        dictRemoveFile: 'حذف فایل',
        dictRemoveFileConfirmation: 'آیا مطمئن هستید؟',
        init: function () {
            thisDropzone = this;
            $.get(url_path, function (data) {
                $.each(data, function (key, value) {

                    var mockFile = {name: value.name, size: value.size};

                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);

                    if (typeof value.path != 'undefined') {
                        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, value.path);
                    }
                });
            });
        },
        removedfile: function (file) {
            var file_name = file.name;
            // dz-remove
            $.get(url_path_del, {file_name: file.name}, function (data, status) {
                var opts;

                if (status == 'success') {
                    switch (data) {
                        case '0':
                            opts = {
                                title: "Error",
                                text: "Delete failed! Please try again",
                                addclass: "stack-bottom-left bg-danger",
                                type: "error"
                            };
                            break;
                        case '1':
                            opts = {
                                title: "Success",
                                text: "File successfully deleted",
                                addclass: "stack-bottom-left bg-success",
                                type: "success"
                            };

                            if (file.previewElement != null && file.previewElement.parentNode != null) {
                                file.previewElement.parentNode.removeChild(file.previewElement);
                            }
                            break;
                        case '2':
                            opts = {
                                title: "Error",
                                text: "File does not exists",
                                addclass: "stack-bottom-left bg-danger",
                                type: "error"
                            };
                            break;
                    }
                } else {
                    opts = {
                        title: "Error",
                        text: "Error in sent file for delete operation.Error status:" + status,
                        addclass: "stack-bottom-left bg-danger",
                        type: "error"
                    };
                }

                new PNotify(opts);
            });
        }
    });
});
