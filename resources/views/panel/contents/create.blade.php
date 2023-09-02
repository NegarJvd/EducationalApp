@extends('panel.layouts.app')

@section('head_styles')
    <link href='{{asset('css/basic.min.css')}}' rel='stylesheet'>
    <link href='{{asset('css/dropzone.min.css')}}' rel='stylesheet'>
@endsection

@section('content')

    <div class="breadcrumb-wrapper">
        <h1>مدیریت محتوا</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/panel')}}">
                        <span class="mdi mdi-home"></span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('panel.contents.index')}}">
                        محتوا ها
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ایجاد محتوا
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>ایجاد محتوا</h2>
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            {!! Form::open(array('route' => 'panel.contents.store', 'method'=>'POST', 'id' => 'content_create_form')) !!}

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="name">نام<b class="text-primary">*</b></label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('name', null, array('class' => 'form-control', 'id'=>'name', 'required' => 'required')) !!}
                                    {!! Form::text('cover_id', null, array('class' => 'form-control', 'id'=>'file_id', 'hidden' => 'hidden')) !!}
                                </div>

                            </div>

                            {!! Form::close() !!}

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="cover_id">تصویر کاور<b class="text-primary">*</b></label>
                                </div>

                                <div class="col-12 col-md-7" id="index">
                                    <form action="{{url('panel/upload')}}" method="POST" class="dropzone" id="index_picture_dropzone">
                                        @csrf
                                        <input name="type" value="content_cover" hidden />
                                        <div class="dz-message" data-dz-message>
                                            <h5 class="m-dropzone__msg-title">
                                                فایل خود را انتخاب کنید یا در این کادر رها کنید.
                                            </h5>
                                            <span class="m-dropzone__msg-desc">امکان اپلود 1 تصویر</span>
                                        </div>
                                    </form>
                                </div>

                            </div>

                            <div class="d-flex justify-content-center mt-5">
                                <button type="button" class="ladda-button btn btn-primary mb-2 btn-pill" id="submit_button">
                                    <span class="ladda-label">ذخیره</span>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/dropzone.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#submit_button').on('click', function () {
                $('#content_create_form').submit();
            });
        })

        Dropzone.options.indexPictureDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            uploadMultiple: false,
            dictRemoveFile: 'حذف تصویر',
            acceptedFiles: "image/*,",
            dictMaxFilesExceeded: 'امکان آپلود فایل بیشتر وجود ندارد.',
            maxFiles: 1,
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Accept': 'application/json'
            },
            addRemoveLinks: true,
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

        function delete_profile(id) {
            if(confirm("برای حذف تصویر پروفایل خود مطمئن هستید؟")){
                $.ajax({
                    url: '/panel/delete_file/' + id,
                    type: 'DELETE',
                    async: true,
                    dataType: 'json',
                    success: function (data, textStatus, jQxhr) {
                        response = JSON.parse(jQxhr.responseText);
                        console.log(response);
                        alert("تصویر با موفقیت حذف شد.");
                        location.reload();
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        response = JSON.parse(jqXhr.responseText);
                        console.log(response);
                        alert("حذف تصویر با مشکل روبه رو شده است.");
                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }
        }
    </script>
@endsection
