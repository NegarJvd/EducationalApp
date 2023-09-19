function error_function(file, response){
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

function remove_file_function(id, which){
    $.ajax({
        url: '/panel/delete_file/' + id,
        type: 'DELETE',
        async: true,
        dataType: 'json',
        success: function (data, textStatus, jQxhr) {
            response = JSON.parse(jQxhr.responseText);
            swal("موفقیت آمیز", response.message, "success");

            if(which === "cover"){
                $('#new_step_cover_id').val('').change();
            }else if(which === "video"){
                $('#new_step_video_id').val('').change();
            }
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
}

Dropzone.options.newStepCoverForm = {
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
        this.on("removedfile", file => {
            let id = $('#new_step_cover_id').val()

            remove_file_function(id, "cover")
        });
    },
    accept: function(file, done) {
        done();
    },
    success: function (file, response) {
        $('#new_step_cover_id').val(response.data.id).change();
    },
    error: function (file, response) {
        error_function(file, response)
    }
};

Dropzone.options.newStepVideoForm = {
    paramName: "file", // The name that will be used to transfer the file
    uploadMultiple: false,
    dictRemoveFile: 'حذف ویدیو',
    dictCancelUpload: 'لغو بارگذاری',
    acceptedFiles: "video/*,",
    dictMaxFilesExceeded: 'امکان آپلود بیش از 1 فایل وجود ندارد و فقط ویدیو اول در سیستم ثبت شده است.',
    maxFiles: 1,
    headers: {
        'X-CSRF-Token': $('input[name="_token"]').val(),
        'Accept': 'application/json'
    },
    addRemoveLinks: true,
    init: function() {
        this.on("removedfile", file => {
            let id = $('#new_step_video_id').val()

            remove_file_function(id, "video")
        });
    },
    accept: function(file, done) {
        done();
    },
    success: function (file, response) {
        $('#new_step_video_id').val(response.data.id).change();
    },
    error: function (file, response) {
        error_function(file, response)
    }
};
