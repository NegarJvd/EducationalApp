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
                    <a href="{{route('panel.contents.edit', $content->id)}}">
                        {{$content->name}}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    {{$cluster->name}}
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>ویرایش اطلاعات دسته بندی</h2>
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            {!! Form::model($cluster, ['method' => 'PATCH','route' => ['panel.contents.clusters.update', [$content->id, $cluster->id]], 'id' => 'cluster_update_form']) !!}

                            <div class="col-12 row">
                                <div class="col-6">
                                    {!! Form::text('name', null, array('class' => 'form-control', 'id'=>'name')) !!}
                                </div>

                                <div class="col-6">
                                    {!! Form::textarea('description', null, array('class' => 'form-control', 'id'=>'description', 'rows' => '4', 'style' => 'height:40%;')) !!}
                                </div>

                                <div class="col-6">
                                    {!! Form::text('cover_id', null, array('hidden' => 'hidden', 'id'=>'file_id')) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                            <div class="col-12 d-flex justify-content-center mb-3">
                                <div class="col-6" id="index">
                                    <form action="{{url('panel/upload')}}" method="POST" class="dropzone" id="index_picture_dropzone">
                                        @csrf
                                        <input name="type" value="cluster_cover" hidden />
                                        <input name="content_id" value="{{$content->id}}" hidden />
                                        <div class="dz-message" data-dz-message>
                                            <h5 class="m-dropzone__msg-title">
                                                فایل خود را انتخاب کنید یا در این کادر رها کنید.
                                            </h5>
                                            <span class="m-dropzone__msg-desc">امکان اپلود 1 تصویر</span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="button" class="ladda-button btn btn-primary mb-2 btn-pill" id="submit_button">
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
                    <h2>لیست مراحل {{$cluster->name}}</h2>
                </div>

                <div class="card-body">

                    @foreach($steps as $step)
                        <div class="row card p-3 mb-3 d-flex justify-content-center">
                            <div class="col-12 row justify-content-between align-self-center mb-2" id="{{$step->id}}">
                                <div class="col-6 p-0">
                                    مرحله {{$step->number}} :
                                </div>

                                <div class="col-6 p-0">

                                    {!! Form::open(['method' => 'DELETE','route' => ['panel.contents.clusters.steps.destroy', [$content->id, $cluster->id, $step->id]], 'onsubmit' => 'return confirm("با حدف این مرحله، تمام فایل ها و رکورد های ثبت شده توسط مراجعه کنندگان حذف خواهند شد. برای این کار مطمئن هستید؟")']) !!}
                                    <button type="submit" class="btn btn-sm btn-outline-primary float-right" title="حذف"><span class="mdi mdi-trash-can"></span></button>
                                    {!! Form::close() !!}

                                    {!! Form::model($step, ['method' => 'PATCH','route' => ['panel.contents.clusters.steps.update', [$content->id, $cluster->id, $step->id]], 'class' => ['update_step']]) !!}
                                        {!! Form::textarea('description', null, ['hidden' => 'hidden', 'class' => ['description']]) !!}
                                        {!! Form::text('cover_id', null, ['hidden' => 'hidden', 'class' => ['cover_id']]) !!}
                                        {!! Form::text('video_id', null, ['hidden' => 'hidden', 'class' => ['video_id']]) !!}
                                        <button type="submit" class="btn btn-sm btn-outline-primary float-right" title="ویرایش"><span class="mdi mdi-square-edit-outline"></span></button>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div class="col-12 row justify-content-center align-self-center mb-2">
                                <textarea class="form-control col-12 description_for_edit">{{$step->description}}</textarea>
                            </div>

                            <div class="col-12 row justify-content-center align-self-center mb-2">

                            </div>
                        </div>
                    @endforeach

                    {!! $steps->links() !!}

                    <div class="row card p-3 mb-3 d-flex justify-content-center border border-secondary">
                        {!! Form::open(['method' => 'POST','route' => ['panel.contents.clusters.steps.store', [$content->id, $cluster->id]], 'id' => 'new_step_form']) !!}
                        <div class="col-12 row justify-content-between align-self-center mb-2">
                            <div class="col-6 p-0">
                                مرحله {{$steps->total() + 1}} :
                            </div>
                        </div>

                        <div class="col-12 row justify-content-center align-self-center mb-2">
                            {!! Form::textarea('description', null, ['class' => 'form-control col-12', 'placeholder' => 'توضیحات ...', 'rows' => '4', 'style' => 'height:100%;']) !!}
                        </div>

                        <input name="cover_id" id="new_step_cover_id" hidden>
                        <input name="video_id" id="new_step_video_id" hidden>

                        {!! Form::close() !!}

                        <div class="col-12 row justify-content-center align-self-center mb-2">

                            <div class="col-6" id="new_step_cover_div">
                                <form action="{{url('panel/upload')}}" method="POST" class="dropzone" id="new_step_cover_form">
                                    @csrf
                                    <input name="type" value="step_cover" hidden />
                                    <input name="content_id" value="{{$content->id}}" hidden />
                                    <input name="cluster_id" value="{{$cluster->id}}" hidden />
                                    <div class="dz-message" data-dz-message>
                                        <h5 class="m-dropzone__msg-title">
                                            فایل<b class="text-primary"> تصویر کاور </b> خود را انتخاب کنید یا در این کادر رها کنید.
                                        </h5>
                                        <span class="m-dropzone__msg-desc">امکان اپلود 1 تصویر</span>
                                    </div>
                                </form>
                            </div>

                            <div class="col-6" id="new_step_video_div">
                                <form action="{{url('panel/upload')}}" method="POST" class="dropzone" id="new_step_video_form">
                                    @csrf
                                    <input name="type" value="step_video" hidden />
                                    <input name="content_id" value="{{$content->id}}" hidden />
                                    <input name="cluster_id" value="{{$cluster->id}}" hidden />
                                    <div class="dz-message" data-dz-message>
                                        <h5 class="m-dropzone__msg-title">
                                            فایل<b class="text-primary"> ویدیو </b> خود را انتخاب کنید یا در این کادر رها کنید.
                                        </h5>
                                        <span class="m-dropzone__msg-desc">امکان اپلود 1 ویدیو</span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="button" class="ladda-button btn btn-primary mb-2 btn-pill" id="new_step_submit_button">
                                <span class="ladda-label">ذخیره</span>
                                <span class="ladda-spinner"></span>
                            </button>
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
    <script src="{{asset('js/custom_js/steps.js')}}"></script>

    <script>
        //description_for_edit
        $(document).ready(function() {
            $('#submit_button').on('click', function () {
                $('#cluster_update_form').submit();
            });

            $('#new_step_submit_button').on('click', function () {
                $('#new_step_form').submit();
            });

            $('.update_step').on('submit', function() {
                var form_description = $(this).find('.description');
                var form_cover_id = $(this).find('.cover_id');
                var form_video_id = $(this).find('.video_id');

                var parent = $(this).parent().parent().parent();
                var description = parent.find('.description_for_edit').val();

                form_description.val(description);
                form_cover_id.val(1);
                form_video_id.val(1);

                return true;
            });
        });
    </script>
@endsection
