@extends('panel.layouts.app')

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
                    پروفایل مدیر
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
                            @if(!is_null($admin->avatar_id))
                                <img width="100%" src="{{asset($admin->avatar_path())}}" alt="admin image">
                            @else
                                <img width="100%" src="{{asset('/assets/img/account.png')}}" alt="admin image">
                            @endif
                        </div>

                        <div class="card-body">
                            <h4 class="py-2 text-dark">
                                @switch($admin->status)
                                    @case("active")
                                    <span class="mdi mdi-check-circle-outline text-success" title="فعال"></span>
                                    @break

                                    @case("inactive")
                                    <span class="mdi mdi-close-circle-outline text-danger" title="غیر فعال"></span>
                                    @break
                                @endswitch
                                {{$admin->name}}
                            </h4>
                            <p>{{$admin->phone}}</p>
                            <p>{{$admin->email}}</p>
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

                        @can('admin-list')
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="more_info-tab" data-toggle="tab" href="#more_info" role="tab" aria-controls="more_info" aria-selected="false">اطلاعات کاری</a>
                            </li>
                        @endcan

                        @can('role-list')
                            <li class="nav-item">
                                <a class="nav-link" id="role-tab" data-toggle="tab" href="#role" role="tab" aria-controls="role" aria-selected="false">نقش</a>
                            </li>
                        @endcan

                    </ul>

                    <div class="tab-content px-3 px-xl-5" id="myTabContent">

                        @can('admin-list')
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-pane-content mt-5">

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="first_name">نام</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="first_name">{{$admin->first_name}}</strong>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="last_name">نام خانوادگی</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="last_name">{{$admin->last_name}}</strong>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="phone">شماره همراه</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="phone">{{$admin->phone}}</strong>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="landline_phone">تلفن ثابت</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="landline_phone">{{$admin->landline_phone}}</strong>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="email">ایمیل</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="email">{{$admin->email}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="gender">جنسیت</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="gender">
                                            @switch($admin->gender)
                                                @case('male')
                                                مرد
                                                @break
                                                @case('female')
                                                زن
                                                @break
                                            @endswitch
                                        </strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="birth_date">تاریخ تولد</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="birth_date">{{$admin->birth_date ? \Morilog\Jalali\Jalalian::forge($admin->birth_date)->format('%Y/%m/%d') : ''}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="address">آدرس</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="address">{{$admin->address}}</strong>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="more_info" role="tabpanel" aria-labelledby="more_info-tab">
                            <div class="tab-pane-content mt-5">

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="medical_system_number">کد نظام پزشکی</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="medical_system_number">{{$admin->medical_system_number}}</strong>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="field_of_profession">حیطه کاری</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="field_of_profession">{{$admin->field_of_profession}}</strong>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="resume">سوابق کاری</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="resume">{{$admin->resume}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="degree_of_education">مدرک تحصیلی</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="degree_of_education">{{$admin->degree_of_education}}</strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endcan

                        @can('role-list')
                            <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab">
                                <div class="tab-pane-content mt-5">
                                    <div class="form-group row">
                                        <div class="col-12 col-md-2 text-left">
                                            <label for="roles">نقش</label>
                                        </div>

                                        <div class="col-12 col-md-7">

                                            <ul>
                                            @foreach($admin->getRoleNames() as $v)
                                                <li id="roles"><strong>{{ $v }}</strong></li>
                                            @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
