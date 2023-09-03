Dropzone.options.indexPictureDropzone = {
    paramName: "file", // The name that will be used to transfer the file
    uploadMultiple: false,
    dictRemoveFile: 'حذف تصویر',
    dictCancelUpload: 'لغو بارگذاری',
    acceptedFiles: "image/*,",
    dictMaxFilesExceeded: 'امکان آپلود فایل بیشتر وجود ندارد.',
    maxFiles: 1,
    headers: {
        'X-CSRF-Token': $('input[name="_token"]').val(),
        'Accept': 'application/json'
    },
    addRemoveLinks: true,
    init: function() {
        this.on("removedfile", file => {
            let id = $('#file_id').val()

            $.ajax({
                url: '/panel/delete_file/' + id,
                type: 'DELETE',
                async: true,
                dataType: 'json',
                success: function (data, textStatus, jQxhr) {
                    response = JSON.parse(jQxhr.responseText);
                    console.log(response.message);
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    response = JSON.parse(jqXhr.responseText);
                    console.log(response.message);
                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    },
    accept: function(file, done) {
        done();
    },
    success: function (file, response) {
        $('#file_id').val(response.data.id).change();

        var picture_show_div = $('#index_picture').parent();
        picture_show_div.empty();

        picture_show_div.append(
            '<div class="col" id="index_picture_show_div">' +
            '<div class="mdi mdi-close btn btn-sm btn-outline-danger btn-pill" id="delete_upload"></div>'+
            '<img width="100%" src="' + response.data.file_path  + '" id="index_picture_show" />'+
            '</div>'
        );
    },
    error: function (file, response) {
        $('#file_id').val('').change();

        if (response.hasOwnProperty('errors') && response.errors.hasOwnProperty('file')){
            var errors = response.errors.file;
            for (i=0; i<errors.length; i++){
                swal("خطا!", errors[i], "error");
            }
        }else {
            swal("خطا!", "خطای سرور...", "error");
        }

    }
};
