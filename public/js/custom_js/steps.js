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
            }else if(which === "update_cover"){
                $('#update_step_cover_id').val('').change();
            }else if(which === "update_video"){
                $('#update_step_video_id').val('').change();
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

function update_dropzone(which, file_id = ''){
    let my_dropzone;
    let form_element_id;

    if(which === "cover"){
        my_dropzone = Dropzone.forElement("#update_step_cover_form");
        form_element_id = "#update_step_cover_form";
    }else if(which === "video"){
        my_dropzone = Dropzone.forElement("#update_step_video_form");
        form_element_id = "#update_step_video_form";
    }

    my_dropzone.emit("resetFiles");
    my_dropzone.destroy();

    new Dropzone(form_element_id, {
        paramName: "file", // The name that will be used to transfer the file
        uploadMultiple: false,
        dictRemoveFile: (which === 'cover') ? "حذف تصویر" : "حذف ویدیو",
        dictCancelUpload: 'لغو بارگذاری',
        acceptedFiles: (which === 'cover') ? "image/*," : "video/*,",
        dictMaxFilesExceeded: 'امکان آپلود بیش از 1 فایل وجود ندارد.',
        maxFiles: 1,
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val(),
            'Accept': 'application/json'
        },
        addRemoveLinks: true,
        init: function() {
            let dropzone = this;

            if(file_id !== ''){
                $.ajax({
                    url: '/panel/fetch_file/' + file_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        var mockFile = {
                            name: response.data.file_name,
                            size: response.data.size,
                            accepted: true,
                            status: 'success'
                        };

                        dropzone.emit("addedfile", mockFile);
                        if(which === 'cover'){
                            dropzone.emit("thumbnail", mockFile, response.data.file_path);
                        }else if(which === 'video'){
                            dropzone.emit("thumbnail", mockFile, "/assets/img/play_icon.png");
                        }

                        dropzone.emit("complete", mockFile);
                        dropzone.files.push( mockFile );
                        dropzone.options.maxFiles = 0;

                        $('[data-dz-thumbnail]').css('height', '120');
                        $('[data-dz-thumbnail]').css('width', '120');
                        $('[data-dz-thumbnail]').css('object-fit', 'cover');
                    }
                });
            }

            this.on("removedfile", function (file, hardRemove = 1) {
                if(hardRemove){
                    if(which === 'cover'){
                        remove_file_function(file_id, "update_cover");
                    }else if(which === 'video'){
                        remove_file_function(file_id, "update_video")
                    }
                }

                dropzone.options.maxFiles = 1;
            });

            this.on("resetFiles", function () {
                for (let file of this.files) {
                    this.emit("removedfile", file, 0);
                }
                this.files = [];
                dropzone.options.maxFiles = 1;
                return this.emit("reset");
            });
        },
        accept: function(file, done) {
            done();
        },
        success: function (file, response) {
            if(which === "cover"){
                $('#update_step_cover_id').val(response.data.id).change();
            }else if(which === "video"){
                $('#update_step_video_id').val(response.data.id).change();
            }

        },
        error: function (file, response) {
            error_function(file, response)
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

$('.update_step').on('click', function () {
    let step_number = $(this).attr('step_number')
    let route = $(this).attr('route')

    $('#edited_step_number').text(step_number)
    $('#update_step_form').attr('action', route)

    let parent = $(this).parent().parent().parent();
    let cover_id = parent.find('.step_cover_id').val();
    let video_id = parent.find('.step_video_id').val();

    $('#update_step_cover_id').val(cover_id).change();
    $('#update_step_video_id').val(video_id).change();

    update_dropzone('cover', cover_id);
    update_dropzone('video', video_id);
});
