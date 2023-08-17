@extends('panel.layouts.app')

@section('head_styles')
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
                            {!! Form::open(array('route' => 'panel.contents.store', 'method'=>'POST')) !!}

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="name">نام<b class="text-primary">*</b></label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('name', null, array('class' => 'form-control', 'id'=>'name', 'required' => 'required')) !!}
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="cover_id">تصویر کاور<b class="text-primary">*</b></label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('cover_id', null, array('class' => 'form-control', 'id'=>'cover_id')) !!}
                                </div>

                            </div>

                            <div class="d-flex justify-content-center mt-5">
                                <button type="submit" class="ladda-button btn btn-primary mb-2 btn-pill">
                                    <span class="ladda-label">ذخیره</span>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/dropzone.min.js')}}"></script>
    <script>
        Dropzone.options.contentEditDropzone = {
            paramName: "file", // The name that will be used to transfer the file
            uploadMultiple: false,
            acceptedFiles: "image/*,",
            dictRemoveFile: 'حذف تصویر',
            dictMaxFilesExceeded: 'امکان آپلود فایل بیشتر وجود ندارد.',
            maxFiles: 1,
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            accept: function(file, done) {
                done();
            }
        };

        function delete_profile(id) {
            if(confirm("برای حذف تصویر کاور محتوا مطمئن هستید؟")){
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
