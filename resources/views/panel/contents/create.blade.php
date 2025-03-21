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
                                            <p class="m-dropzone__msg-title">
                                                فایل خود را انتخاب کنید یا در این کادر رها کنید.
                                            </p>
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
    <script src="{{asset('js/custom_js/dropzone_upload_file.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#submit_button').on('click', function () {
                $('#content_create_form').submit();
            });
        })
    </script>
@endsection
