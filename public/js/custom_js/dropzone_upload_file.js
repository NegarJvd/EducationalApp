Dropzone.options.indexPictureDropzone = {
    paramName: "file", // The name that will be used to transfer the file
    uploadMultiple: false,
    dictRemoveFile: 'حذف تصویر',
    dictCancelUpload: 'لغو بارگذاری',
    acceptedFiles: "image/*,",
    dictMaxFilesExceeded: 'امکان آپلود بیش از 1 فایل وجود ندارد و فقط تصویر اول در سیستم ثبت شده است.',
    maxFiles: 1,
    headers: {
        'X-CSRF-Token': $('input[name="_token"]').val(),
        'Accept': 'application/json'
    },
    addRemoveLinks: true,
    init: function() {
        let myDropzone = this;
        let id = $('#file_id').val()

        if(id !== ''){
            $.ajax({
                url: '/panel/fetch_file/' + id,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    var mockFile = {name: response.data.file_name, size: response.data.size};

                    myDropzone.emit("addedfile", mockFile);
                    myDropzone.emit("thumbnail", mockFile, response.data.file_path);
                    myDropzone.emit("complete", mockFile);
                    myDropzone.files.push( mockFile );
                    myDropzone.options.maxFiles = 0;

                    $('[data-dz-thumbnail]').css('height', '120');
                    $('[data-dz-thumbnail]').css('width', '120');
                    $('[data-dz-thumbnail]').css('object-fit', 'cover');

                }
            });
        }

        this.on("removedfile", file => {
            let id = $('#file_id').val()

            $.ajax({
                url: '/panel/delete_file/' + id,
                type: 'DELETE',
                async: true,
                dataType: 'json',
                success: function (data, textStatus, jQxhr) {
                    $('#file_id').val('').change();
                    myDropzone.options.maxFiles = 1;

                    response = JSON.parse(jQxhr.responseText);
                    swal("موفقیت آمیز", response.message, "success");
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    response = JSON.parse(jqXhr.responseText);
                    swal("خطا!", response.message, "error");
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
    },
    error: function (file, response) {
        console.log(response)

        if (response.hasOwnProperty('errors') && response.errors.hasOwnProperty('file')){
            var errors = response.errors.file;
            for (i=0; i<errors.length; i++){
                swal("خطا!", errors[i], "error");
            }
        }if (response.hasOwnProperty('message')){
            swal("خطا!", response.message, "error");
        }else {
            swal("خطا!", response, "error");
        }

    }
};
