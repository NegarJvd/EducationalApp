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
                    {{$content->name}}
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
                        <div class="col-md-12 col-xl-12 row">
                            {!! Form::model($content, ['method' => 'PATCH','route' => ['panel.contents.update', $content->id], 'class' => 'col-4 align-self-center', 'id' => 'content_update_form']) !!}

                                {!! Form::text('name', null, array('class' => 'form-control', 'id'=>'name')) !!}
                                {!! Form::text('cover_id', null, array('id'=>'file_id', 'hidden' => 'hidden')) !!}

                            {!! Form::close() !!}

                            <div class="col-6" id="index">
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

                            <div class="col-2 align-self-center">
                                <button type="button" class="ladda-button btn btn-primary mb-2 btn-pill float-right" id="submit_button">
                                    <span class="ladda-label">ویرایش</span>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست دسته بندی های {{$content->name}}</h2>

                    @can('content-create')
                        <a href="{{ route('panel.contents.clusters.create', $content->id) }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن دسته بندی">
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
                            <div class="col-md-6 col-xl-4">
                                <div class="card mb-4 p-0">
                                    <h5 class="card-title text-primary pt-4 pb-2 px-3">
                                        <a href="{{route('panel.contents.clusters.edit', [$content->id, $cluster->id])}}">
                                            {{$cluster->name}}
                                        </a>
                                    </h5>

                                    <img class="card-img rounded-0" src="{{$cluster->cover_image ? $cluster->cover_image : asset('/assets/img/image-placeholder.png')}}">

                                    <div class="card-body row">

                                        <div class="col-10">
                                            <p class="card-text pb-2">
                                                {{$cluster->description}}
                                            </p>
                                        </div>

                                        <div class="col-2 justify-content-center d-flex align-items-end">
                                            @can('content-delete')
                                                {!! Form::open(['method' => 'DELETE','route' => ['panel.contents.clusters.destroy', [$content->id, $cluster->id]], 'onsubmit' => 'return confirm("با حدف دسته بندی، تمام فایل ها و رکورد های ثبت شده توسط مراجعه کنندگان حذف خواهند شد. برای این کار مطمئن هستید؟")']) !!}
                                                <button type="submit" class="btn btn-sm btn-outline-primary float-right" title="حذف"><span class="mdi mdi-18px mdi-trash-can"></span></button>
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>

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
    <script src="{{asset('js/custom_js/dropzone_upload_file.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#submit_button').on('click', function () {
                $('#content_update_form').submit();
            });
        })
    </script>
@endsection
