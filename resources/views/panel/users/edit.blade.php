@extends('panel.layouts.app')

@section('head_styles')
    <link href='{{asset('css/persian-datepicker.min.css')}}' rel='stylesheet'>
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
                    <a href="{{route('panel.users.index')}}">
                        مراجعه کنندگان
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ویرایش مراجعه کننده
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

                    @can('user-edit')
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="medical-tab" data-toggle="tab" href="#medical" role="tab" aria-controls="medical">اطلاعات درمانی</a>
                    </li>
                    @endcan

                    @can('content-list')
                        @if($user->admin_id == \Illuminate\Support\Facades\Auth::id())
                            <li class="nav-item">
                                <a class="nav-link" id="contents-tab" data-toggle="tab" href="#contents" role="tab" aria-controls="contents">محتوا ها</a>
                            </li>
                        @endif
                    @endcan

                </ul>

                <div class="tab-content px-3 px-xl-5" id="myTabContent">

                    @can('user-edit')
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

                            {!! Form::model($user, ['method' => 'PATCH','route' => ['panel.users.update', $user->id]]) !!}

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
                                        <option value="male" {{$user->gender == 'male'? 'selected': ''}}>مرد</option>
                                        <option value="female" {{$user->gender == 'female'? 'selected': ''}}>زن</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="birth_date">تاریخ تولد</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <input type="text" class="form-control" id="birth_date" value="{{$user->birth_date}}">
                                    <input type="text" class="form-control" name="birth_date" id="alt_birth_date" value="{{strtotime($user->birth_date)}}" hidden>
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

                            <div class="d-flex justify-content-center mt-5">
                                <button type="submit" class="ladda-button btn btn-primary mb-2 btn-pill">
                                    <span class="ladda-label">ویرایش</span>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="tab-pane fade" id="medical" role="tabpanel" aria-labelledby="medical-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

                            {!! Form::model($user, ['method' => 'PATCH','route' => ['panel.users.update', $user->id]]) !!}

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="admin_id">درمانگر</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <select class="form-control" id="admin_id" name="admin_id">
                                        <option value=""></option>
                                        @foreach(\App\Models\Admin::all() as $admin)
                                            <option value="{{$admin->id}}" {{$user->admin_id == $admin->id? 'selected': ''}}>{{$admin->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="first_visit">اولین مراجعه</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <input type="text" class="form-control" id="first_visit" value="{{$user->first_visit}}">
                                    <input type="text" class="form-control" name="first_visit" id="alt_first_visit" value="{{strtotime($user->first_visit)}}" hidden>
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
                                    <span class="ladda-label">ویرایش</span>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                    @endcan

                    @can('content-list')
                            @if($user->admin_id == \Illuminate\Support\Facades\Auth::id())
                                <div class="tab-pane fade" id="contents" role="tabpanel" aria-labelledby="contents-tab">
                                    <div class="tab-pane-content mt-5">

                                        @include('panel.panel_message')

                                        <div class="row col-12">
                                            <div class="col-5">
                                                <select class="form-control" id="contents_select">
                                                    <option value=""></option>
                                                    @foreach(\App\Models\Content::all() as $content)
                                                        <option value="{{$content->id}}">{{$content->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-5">
                                                <select disabled class="form-control" id="clusters_select"></select>
                                            </div>
                                            <div class="col-2">
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="ladda-button btn btn-primary mb-2 btn-pill" id="add_content" disabled>
                                                        <span class="ladda-label">افزودن</span>
                                                        <span class="ladda-spinner"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table text-center font-size-14">
                                            <thead>
                                            <tr>
                                                <th>محتوا</th>
                                                <th>دسته بندی</th>
                                                <th>عملیات</th>
                                            </tr>
                                            </thead>

                                            <tbody id="contents_table_body">
                                                @foreach($clusters as $cluster)
                                                    <tr>
                                                        <td>{{$cluster->content->name}}</td>
                                                        <td>{{$cluster->name}}</td>
                                                        <td>
                                                            @can('user-evaluation')
                                                                <input hidden value="{{$cluster->id}}" class="cluster_id">
                                                                <button type="button" class="btn p-0 view_actions" data-toggle="modal" data-target="#actions_charts"
                                                                        title="عملكرد">
                                                                    <span class="mdi mdi-eye-outline mdi-dark mdi-18px"></span>
                                                                </button>
                                                                <button type="button" class="btn p-0 delete_content" title="حذف">
                                                                    <span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span>
                                                                </button>

                                                            @endcan
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        {{ $clusters->links() }}
                                    </div>
                                </div>
                            @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{asset('js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('js/persian-date.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

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

    @if($user->admin_id == \Illuminate\Support\Facades\Auth::id())
        @can('user-evaluation')
        <script>
            $(document).ready(function() {
                let today = new Date().toLocaleDateString('fa-IR-u-nu-latn');
                let this_month = today.split("/")[1]
                $('#month').val(this_month).change();
            });
        </script>


        <script src="{{asset('js/custom_js/users_contents.js')}}"></script>
        <script src='{{asset('assets/plugins/charts/Chart.min.js')}}'></script>
        <script src="{{asset('js/custom_js/actions_chart.js')}}"></script>

        <div class="modal fade" id="actions_charts" tabindex="-1" role="dialog" aria-labelledby="actions_charts" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle2">عملکرد </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" id="popup_form">
                        <input hidden value="{{$user->id}}" id="user_id">
                        <input hidden value="" id="cluster_id">

                        <div class="row col-12">
                            <div class="col-8">
                                <select class="form-control" id="month">
                                    <option value="1">فروردین</option>
                                    <option value="2">اردیبهشت</option>
                                    <option value="3">خرداد</option>
                                    <option value="4">تیر</option>
                                    <option value="5">مرداد</option>
                                    <option value="6">شهریور</option>
                                    <option value="7">مهر</option>
                                    <option value="8">آبان</option>
                                    <option value="9">آذر</option>
                                    <option value="10">دی</option>
                                    <option value="11">بهمن</option>
                                    <option value="12">اسفند</option>
                                </select>
                                <br>
                            </div>

                            <div class="col-4">
                                <b> آخرین امتیاز :</b>
                                <span id="last_action_score"></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card card-default">
                                <div class="card-body charts_div" style="min-height: 450px;">
                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 400px">
                                        <div class="sk-folding-cube">
                                            <div class="sk-cube1 sk-cube"></div>
                                            <div class="sk-cube2 sk-cube"></div>
                                            <div class="sk-cube4 sk-cube"></div>
                                            <div class="sk-cube3 sk-cube"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endcan
    @endif
@endsection
