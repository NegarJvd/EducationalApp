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
                    <a href="{{route('panel.users.index')}}">
                        کاربران
                    </a>
                </li>
                <li class="breadcrumb-item">
                    پروفایل کاربر
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
                            @if(!is_null($user->avatar_id))
                                <img width="100%" src="{{asset($user->avatar_path())}}" alt="user image">
                            @else
                                <img width="100%" src="{{asset('/assets/img/account.png')}}" alt="user image">
                            @endif
                        </div>

                        <div class="card-body">
                            <h4 class="py-2 text-dark">{{$user->name}}</h4>
                            <p>{{$user->phone}}</p>
                            <p>{{$user->email}}</p>
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

                        @can('user-list')
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی</a>
                            </li>
                        @endcan

                        @can('address-list')
                             <li class="nav-item">
                                 <a class="nav-link" id="addresses-tab" data-toggle="tab" href="#addresses" role="tab" aria-controls="addresses">آدرس ها</a>
                             </li>
                        @endcan

                    </ul>

                    <div class="tab-content px-3 px-xl-5" id="myTabContent">

                        @can('user-list')
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-pane-content mt-5">

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="name">نام</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="name">{{$user->name}}</strong>
                                    </div>

                                </div>

{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-12 col-md-2 text-left">--}}
{{--                                        <label for="username">نام کاربری</label>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-12 col-md-7">--}}
{{--                                        <strong id="username">{{$user->username}}</strong>--}}
{{--                                    </div>--}}

{{--                                </div>--}}

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="phone">شماره همراه</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="phone">{{$user->phone}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="landline_phone">تلفن ثابت</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="landline_phone">{{$user->landline_phone}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="email">ایمیل</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="email">{{$user->email}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="gender">جنسیت</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="gender">
                                        @switch($user->gender)
                                            @case('male')
                                            مرد
                                                @break
                                            @case('female')
                                            زن
                                                @break
                                            @case('other')
                                            سایر
                                                @break
                                        @endswitch
                                        </strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="date_of_birth">تاریخ تولد</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="date_of_birth">{{$user->date_of_birth ? \Morilog\Jalali\Jalalian::forge($user->date_of_birth)->format('%Y/%m/%d') : ''}}</strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12 col-md-2 text-left">
                                        <label for="city_id">شهر</label>
                                    </div>

                                    <div class="col-12 col-md-7">
                                        <strong id="city_id">{{@$user->city->name}} {{@$user->city->state ? (' - ' . @$user->city->state->name) : ''}}</strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endcan

                        @can('address-list')
                                <div class="tab-pane fade" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                                    <div class="tab-pane-content mt-5">
                                        <input id="user_id" hidden readonly value="{{$user->id}}">

                                        <div class="form-group">
                                            <div id="map-canvas" style="height: 400px"></div>
                                        </div>

                                        <table class="table table-striped text-center">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>شهر</th>
                                                <th>نام</th>
                                                <th>آدرس</th>
                                            </tr>
                                            </thead>

                                            <tbody id="addresses_table_body">
                                            @foreach($addresses as $address)
                                                <tr>
                                                    <td>{{$address->id}}</td>
                                                    <td>
                                                        {{@$address->city->name}}
                                                    </td>
                                                    <td>
                                                        {{$address->name}}
                                                    </td>
                                                    <td>
                                                        {{$address->address}}
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
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
    <script src="{{asset('js/custom_js/address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1lgc4S8VAc6DS6K1fuCc53DOHp2jncYs&libraries=places&callback=initialize"></script>

    <script>
        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endsection
