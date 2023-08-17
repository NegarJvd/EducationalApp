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
                    دسته بندی ها
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>ویرایش اطلاعات محتوا</h2>
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            {!! Form::model($content, ['method' => 'PATCH','route' => ['panel.contents.update', $content->id], 'class' => 'row']) !!}

                                {!! Form::text('name', null, array('class' => 'form-control col-4', 'id'=>'name')) !!}

{{--                                    <div class="dz-message col-6" data-dz-message>--}}
{{--                                        <h5 class="m-dropzone__msg-title">--}}
{{--                                            فایل خود را انتخاب کنید یا در این کادر رها کنید.--}}
{{--                                        </h5>--}}
{{--                                        <span class="m-dropzone__msg-desc">امکان اپلود 1تصویر</span>--}}
{{--                                    </div>--}}

                                <div class="col-6">

                                </div>

                                <div class="col-2  d-flex justify-content-center">
                                    <button type="submit" class="ladda-button btn btn-primary mb-2 btn-pill">
                                        <span class="ladda-label">ویرایش</span>
                                        <span class="ladda-spinner"></span>
                                    </button>
                                </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست دسته بندی های {{$content->name}}</h2>

                    @can('content-create')
                        <a href="{{ route('panel.contents.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن دسته بندی">
                            <i class="mdi mdi-24px mdi-note-plus"></i>
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="row">
{{--                        <form method="GET" action="{{route('panel.contents.edit',$content->id)}}" id="search_form" class="col-12 row justify-content-between">--}}
{{--                            <div class="search-form d-none d-lg-inline-block col-4">--}}
{{--                                <div class="input-group">--}}
{{--                                    <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="عنوان ..."--}}
{{--                                           value="{{request('search')}}" autofocus autocomplete="off" />--}}
{{--                                    <div class="input-group-append">--}}
{{--                                        <button class="btn btn-outline-secondary" title="جست و جو" type="submit">--}}
{{--                                            <i class="mdi mdi-magnify"></i>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}

                        @foreach($clusters as $cluster)
                            <div class="col-md-6 col-xl-3">
                                <div class="card  mb-4 p-0">
                                    <h5 class="card-title text-primary pt-4 pb-2 px-3">
                                        <a href="">
                                            {{$cluster->name}}
                                        </a>
                                    </h5>

                                    <img class="card-img rounded-0" src="{{$cluster->cover_image}}">

                                    <div class="card-body">
                                        <p class="card-text pb-2">
                                            {{$cluster->description}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {!! $clusters->appends([
                                   'search' => \Request::get('search')
                               ])->render() !!}

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
