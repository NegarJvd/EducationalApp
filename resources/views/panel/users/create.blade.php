@extends('panel.layouts.app')

@section('head_styles')
    <link href='{{asset('css/persian-datepicker.min.css')}}' rel='stylesheet'>
@endsection

@section('content')

    <div class="breadcrumb-wrapper">
        <h1>مدیریت مراجعه کننده
            ان</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/panel')}}">
                        <span class="mdi mdi-home"></span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('panel.users.index')}}">
                        مراجعه کنندگان
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ایجاد مراجعه کننده
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
                            <img width="100%" src="{{asset('/assets/img/account.png')}}" alt="user image">
                        </div>

                        <div class="card-body">
                            <h4 class="py-2 text-dark">
                                مراجعه کننده
                            </h4>
                        </div>
                    </div>

                    <hr class="w-100">

                </div>
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="profile-content-right profile-right-spacing py-5">
                    <ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">
                        @can('user-create')
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی و درمانی</a>
                            </li>
                        @endcan

                    </ul>

                    <div class="tab-content px-3 px-xl-5" id="myTabContent">

                        @can('user-create')
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-pane-content mt-5">

                                @include('panel.panel_message')

                                {!! Form::open(array('route' => 'panel.users.store', 'method'=>'POST')) !!}

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
                                        <label for="father_name">نام پدر<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('father_name', null, array('class' => 'form-control', 'id'=>'father_name', 'required' => 'required')) !!}
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="mother_name">نام مادر<b class="text-primary">*</b></label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('mother_name', null, array('class' => 'form-control', 'id'=>'mother_name', 'required' => 'required')) !!}
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
                                        <input type="text" class="form-control" id="birth_date" value="{{old('birth_date') ? timestamp_to_date(old('birth_date'), 'Y-m-d') : null }}" autocomplete="off">
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
                                        <label for="admin_id">درمانگر</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <select class="form-control" id="admin_id" name="admin_id">
                                            <option value=""></option>
                                            @foreach(\App\Models\Admin::all() as $admin)
                                                <option value="{{$admin->id}}" {{old('admin_id') == $admin->id? 'selected': ''}}>{{$admin->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="first_visit">اولین مراجعه</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <input type="text" class="form-control" id="first_visit" value="{{old('first_visit') ? timestamp_to_date(old('first_visit'), 'Y-m-d') : null }}" autocomplete="off">
                                        <input type="text" class="form-control" name="first_visit" id="alt_first_visit" value="{{old('first_visit')}}" hidden>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="diagnosis">تشخیص</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        {!! Form::text('diagnosis', null, array('class' => 'form-control', 'id'=>'diagnosis')) !!}
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
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/persian-date.min.js')}}"></script>
    <script src="{{asset('js/persian-datepicker.min.js')}}"></script>

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

            var initial_first_visit = $('#alt_first_visit').val();
            if(initial_first_visit){
                $("#first_visit").pDatepicker({
                    autoClose: true,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_first_visit',
                })
            }else{
                $("#first_visit").pDatepicker({
                    autoClose: true,
                    initialValue: false,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_first_visit',
                });
            }

        });
    </script>
@endsection
