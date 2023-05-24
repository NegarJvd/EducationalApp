@extends('panel.layouts.app')

@section('head_styles')
    <link href='{{asset('css/persian-datepicker.min.css')}}' rel='stylesheet'>
    <link href="{{asset('assets/plugins/select2/css/select2.css')}}" rel="stylesheet" />
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
                    ویرایش مدیر
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
                        <h4 class="py-2 text-dark">{{$admin->name}}</h4>
                        <p>{{$admin->phone}}</p>
                        <p>{{$admin->email}}</p>

                        @can('change-admin-status')

                            @switch($admin->status)
                                @case("active")
                                    <a class="ladda-button btn btn-danger btn-pill btn-sm my-4" href="{{route('panel.change_admin_status', $admin->id)}}">
                                        <span class="ladda-label">غیر فعال کردن</span>
                                        <span class="ladda-spinner"></span>
                                    </a>
                                @break

                                @case("inactive")
                                    <a class="ladda-button btn btn-success btn-pill btn-sm my-4" href="{{route('panel.change_admin_status', $admin->id)}}">
                                        <span class="ladda-label">فعال کردن</span>
                                        <span class="ladda-spinner"></span>
                                    </a>
                                @break
                            @endswitch

                        @endcan

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

                    @can('admin-edit')
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی</a>
                    </li>
                    @endcan

                    @can('change_admin_role')
                    <li class="nav-item">
                        <a class="nav-link" id="role-tab" data-toggle="tab" href="#role" role="tab" aria-controls="role" aria-selected="false">نقش</a>
                    </li>
                    @endcan

                </ul>

                <div class="tab-content px-3 px-xl-5" id="myTabContent">

                    @can('admin-edit')
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

                            {!! Form::model($admin, ['method' => 'PATCH','route' => ['panel.admins.update', $admin->id]]) !!}

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="name">نام</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('name', null, array('class' => 'form-control', 'id'=>'name')) !!}
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="username">نام کاربری</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('username', null, array('class' => 'form-control', 'id'=>'username')) !!}
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="phone">شماره همراه</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('phone', null, array('class' => 'form-control', 'id'=>'phone')) !!}
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
                                        <option value="male" {{$admin->gender == 'male'? 'selected': ''}}>مرد</option>
                                        <option value="female" {{$admin->gender == 'female'? 'selected': ''}}>زن</option>
                                        <option value="other" {{$admin->gender == 'other'? 'selected': ''}}>سایر</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="date_of_birth">تاریخ تولد</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <input type="text" class="form-control" id="date_of_birth" value="{{$admin->date_of_birth}}" autocomplete="off">
                                    <input type="text" class="form-control" name="date_of_birth" id="alt_date_of_birth" value="{{strtotime($admin->date_of_birth)}}" hidden>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="city_id">شهر</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <select class="select_city form-control" name="city_id" id="city_id">
                                        @if($admin->city_id)
                                            <option value="{{$admin->city_id}}" selected>{{@$admin->city->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mt-5">
                                <button type="submit" class="ladda-button btn btn-primary mb-2 btn-pill">
                                    <span class="ladda-label">ویرایش</span>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                    @endcan

                    @can('change_admin_role')
                    <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

                            {!! Form::open(['method' => 'POST','route' =>'panel.change_admin_role']) !!}

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="roles">نقش ها</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    @if(in_array(1, $adminRole))
                                        <strong>{{\Spatie\Permission\Models\Role::findById(1)->name}}</strong>
                                    @else

                                        <input value="{{$admin->id}}" name="user_id" hidden>
                                        <select class="form-control" id="roles" name="roles_id[]">
                                            <option></option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}" @if(in_array($role->id, $adminRole)) selected @endif>{{$role->name}}</option>
                                            @endforeach
                                        </select>

                                    @endif
                                </div>
                            </div>


                            @if(!in_array(1, $adminRole))

                                <div class="d-flex justify-content-center mt-5">
                                    <button type="submit" class="ladda-button btn btn-primary mb-2 btn-pill">
                                        <span class="ladda-label">تغيير نقش</span>
                                        <span class="ladda-spinner"></span>
                                    </button>
                                </div>

                            @endif

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
    <script src="{{asset('assets/plugins/select2/js/select2.js')}}"></script>

    <script src="{{asset('js/custom_js/cities.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var initial_date = $('#alt_date_of_birth').val();
            if(initial_date){
                console.log(initial_date)
                $("#date_of_birth").pDatepicker({
                    autoClose: true,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_date_of_birth',
                })
            }else{
                $("#date_of_birth").pDatepicker({
                    autoClose: true,
                    initialValue: false,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_date_of_birth',
                });
            }
        });
    </script>
@endsection
