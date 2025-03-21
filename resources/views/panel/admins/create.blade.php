@extends('panel.layouts.app')

@section('head_styles')
    <link href='{{asset('css/persian-datepicker.min.css')}}' rel='stylesheet'>
    <link href='{{asset('css/dropzone.min.css')}}' rel='stylesheet'>
@endsection

@section('content')

    <div class="breadcrumb-wrapper">
        <h1>مدیریت کاربران</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/panel')}}">
                        <span class="mdi mdi-home"></span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('panel.admins.index')}}">
                        مدیران
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ایجاد مدیر
                </li>
            </ol>
        </nav>

    </div>

    <div class="bg-white border rounded">
        <div class="row no-gutters">
            <div class="col-lg-4 col-xl-3">
                <div class="profile-content-left profile-left-spacing pt-5 pb-3 px-3 px-xl-5">
                    <div class="card text-center widget-profile px-0 border-0">
                        <div class="card-img mx-auto rounded-circle">
                            <img width="100%" src="{{asset('/assets/img/account.png')}}" alt="admin image">
                        </div>

                        <div class="card-body">
                            <h4 class="py-2 text-dark">مدیر</h4>
                        </div>
                    </div>

                    <hr class="w-100">

                    {{--<div class="contact-info pt-4">
                        <h5 class="text-dark mb-1">راه های ارتباطی</h5>
                        <p class="text-dark font-weight-medium pt-4 mb-2">شماره همراه</p>
                        <p></p>
                        <p class="text-dark font-weight-medium pt-4 mb-2">ایمیل</p>
                        <p></p>
                    </div>--}}
                </div>
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="profile-content-right profile-right-spacing py-5">
                    <ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی و کاری</a>
                        </li>
                    </ul>

                    <div class="tab-content px-3 px-xl-5" id="myTabContent">

                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-pane-content mt-5">

                                @include('panel.panel_message')

                                {!! Form::open(array('route' => 'panel.admins.store', 'method'=>'POST', 'id' => 'admin_create_form')) !!}

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="first_name">نام<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('first_name', null, array('class' => 'form-control', 'id'=>'first_name', 'required' => 'required')) !!}
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="last_name">نام خانوادگی<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('last_name', null, array('class' => 'form-control', 'id'=>'last_name', 'required' => 'required')) !!}
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="phone">شماره همراه<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('phone', null, array('class' => 'form-control', 'id'=>'phone', 'required' => 'required')) !!}
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="landline_phone">تلفن ثابت</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('landline_phone', null, array('class' => 'form-control', 'id'=>'landline_phone')) !!}
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="email">ایمیل</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('email', null, array('class' => 'form-control', 'id'=>'email')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="gender">جنسیت</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <select class="form-control" id="gender" name="gender">
                                            <option value=""></option>
                                            <option value="male" {{old('gender') == "male" ? "selected" : "" }}>مرد</option>
                                            <option value="female" {{old('gender') == "female" ? "selected" : "" }}>زن</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="birth_date">تاریخ تولد</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" id="birth_date" autocomplete="off" value="{{old('birth_date') ? timestamp_to_date(old('birth_date'), 'Y-m-d') : null }}">
                                        <input type="text" class="form-control" name="birth_date" id="alt_birth_date" value="{{old('birth_date')}}" hidden>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="address">آدرس</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('address', null, array('class' => 'form-control', 'id'=>'address')) !!}
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="medical_system_number">کد نظام پزشکی<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('medical_system_number', null, array('class' => 'form-control', 'id'=>'medical_system_number', 'required' => 'required')) !!}
                                        {!! Form::text('medical_system_card_id', null, array('class' => 'form-control', 'id'=>'file_id', 'required' => 'required', 'hidden' => 'hidden')) !!}
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="field_of_profession">حیطه کاری</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('field_of_profession', null, array('class' => 'form-control', 'id'=>'field_of_profession')) !!}
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="resume">سوابق کاری</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('resume', null, array('class' => 'form-control', 'id'=>'resume')) !!}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="degree_of_education">مدرک تحصیلی</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <select class="form-control" id="degree_of_education" name="degree_of_education">
                                            <option value=""></option>
                                            @foreach(\App\Models\Admin::degree_of_education() as $degree)
                                                <option value={{$degree}} {{old('degree_of_education') == $degree ? "selected" : "" }}>
                                                    {{$degree}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @can('role-list')
                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="role">نقش<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <select class="form-control" name="role" id="role" required>
                                            <option></option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->name}}" {!! old('role') == $role->name ? 'selected' : '' !!}>{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endcan

                                {!! Form::close() !!}

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="index_picture_dropzone">کارت نظام پزشکی<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">

                                        <form action="{{url('panel/upload')}}" method="POST" class="dropzone" id="index_picture_dropzone">
                                            @csrf
                                            <input name="type" value="medical_system_card" hidden />
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
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/persian-date.min.js')}}"></script>
    <script src="{{asset('js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('js/dropzone.min.js')}}"></script>
    <script src="{{asset('js/custom_js/dropzone_upload_file.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var initial_date = $('#alt_birth_date').val();
            if(initial_date){
                $("#birth_date").pDatepicker({
                    autoClose: true,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_birth_date',
                })
            }else{
                $("#birth_date").pDatepicker({
                    autoClose: true,
                    initialValue: false,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_birth_date',
                });
            }

            $('#submit_button').on('click', function () {
                $('#admin_create_form').submit();
            });
        });
    </script>
@endsection
