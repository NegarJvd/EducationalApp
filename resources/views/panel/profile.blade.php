@extends('panel.layouts.app')

@section('head_styles')
    <link href='{{asset('css/persian-datepicker.min.css')}}' rel='stylesheet'>
    <link href='{{asset('css/bootstrap-slider.css')}}' rel='stylesheet'>
    <link href='{{asset('css/basic.min.css')}}' rel='stylesheet'>
    <link href='{{asset('css/dropzone.min.css')}}' rel='stylesheet'>
{{--    <link href="{{asset('assets/plugins/select2/css/select2.css')}}" rel="stylesheet" />--}}
@endsection

@section('content')
    <div class="breadcrumb-wrapper">
        <h1>پروفایل من</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/panel')}}">
                        <span class="mdi mdi-home"></span>
                    </a>
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
{{--                                <div style="background-image: url({{asset($admin->avatar_path())}}); background-size: cover; background-repeat: no-repeat; width: 100%; height: 100%"></div>--}}
                                <img width="100%" src="{{asset($admin->avatar_path())}}" alt="user image" data-toggle="tooltip" data-placement="top" title="برای حذف تصویر کیلک کنید." id="profile_pic" onclick="delete_profile({{$admin->avatar_id}})">
                            @else
                                <img width="100%" src="{{asset('/assets/img/account.png')}}" alt="user image" data-toggle="modal" data-target="#upload_profile">
                            @endif
                        </div>

                        <div class="card-body">
                            <h4 class="py-2 text-dark">{{$admin->name}}</h4>
                            <p>{{$admin->phone}}</p>
                            <p>{{$admin->email}}</p>
                        </div>
                    </div>

                    <hr class="w-100">

                </div>
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="profile-content-right profile-right-spacing py-5">
                    <ul class="nav nav-tabs px-3 px-xl-5 nav-style-border" id="myTab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی</a>
                            </li>


                        @can('change_admin_role')
                            <li class="nav-item">
                                <a class="nav-link" id="role-tab" data-toggle="tab" href="#role" role="tab" aria-controls="role" aria-selected="false">نقش</a>
                            </li>
                        @endcan

                    </ul>

                    <div class="tab-content px-3 px-xl-5" id="myTabContent">

                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="tab-pane-content mt-5">

                                    @include('panel.panel_message')

                                    {!! Form::model($admin, ['method' => 'PATCH','route' => 'panel.profile']) !!}

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

                                    <div class="d-flex justify-content-center mt-5">
                                        <button type="submit" class="ladda-button btn btn-primary mb-2 btn-pill">
                                            <span class="ladda-label">ویرایش</span>
                                            <span class="ladda-spinner"></span>
                                        </button>
                                    </div>

                                    {!! Form::close() !!}
                                </div>
                            </div>

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

    <div class="modal fade" id="upload_profile" tabindex="-1" role="dialog" aria-labelledby="upload_profile" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle2">آپلود عکس پروفایل</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{url('panel/upload')}}" method="POST" class="dropzone" id="profile_dropzone">
                        @csrf
                        <input name="type" value="avatar" hidden />
                        <div class="dz-message" data-dz-message>
                            <h5 class="m-dropzone__msg-title">
                                فایل خود را انتخاب کنید یا در این کادر رها کنید.
                            </h5>
                            <span class="m-dropzone__msg-desc">امکان اپلود 1تصویر</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="{{asset('js/persian-date.min.js')}}"></script>
    <script src="{{asset('js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>
    <script src="{{asset('js/dropzone.min.js')}}"></script>
{{--    <script src="{{asset('assets/plugins/select2/js/select2.js')}}"></script>--}}

{{--    <script src="{{asset('js/custom_js/cities.js')}}"></script>--}}

    <script type="text/javascript">
        $(document).ready(function() {
            var initial_date = $('#alt_date_of_birth').val();
            if(initial_date){
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

            $(".close").on('click', function () {
                location.reload();
            });
        });
    </script>
    <script>
        Dropzone.options.profileDropzone = {
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
            if(confirm("برای حذف تصویر پروفایل خود مطمئن هستید؟")){
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
