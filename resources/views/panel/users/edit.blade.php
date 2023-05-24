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
                    <a href="{{route('panel.users.index')}}">
                        کاربران
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ویرایش کاربر
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

                        @can('change-user-status')

                            @switch($user->status)
                                @case("active")
                                    <a class="ladda-button btn btn-danger btn-pill btn-sm my-4" href="{{route('panel.change_user_status', $user->id)}}">
                                        <span class="ladda-label">غیر فعال کردن</span>
                                        <span class="ladda-spinner"></span>
                                    </a>
                                @break

                                @case("inactive")
                                    <a class="ladda-button btn btn-success btn-pill btn-sm my-4" href="{{route('panel.change_user_status', $user->id)}}">
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

                    @can('user-edit')
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">اطلاعات فردی</a>
                    </li>
                    @endcan

                    @can('address-edit')
                    <li class="nav-item">
                        <a class="nav-link" id="addresses-tab" data-toggle="tab" href="#addresses" role="tab" aria-controls="addresses">آدرس ها</a>
                    </li>
                    @endcan

                    @can('order-list')
                    <li class="nav-item">
                        <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders">سفارشات</a>
                    </li>
                    @endcan

                    @can('transaction-list')
                    <li class="nav-item">
                        <a class="nav-link" id="transactions-tab" data-toggle="tab" href="#transactions" role="tab" aria-controls="transactions">تراکنش ها</a>
                    </li>
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
                                    <label for="name">نام</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    {!! Form::text('name', null, array('class' => 'form-control', 'id'=>'name')) !!}
                                </div>

                            </div>

{{--                            <div class="form-group row">--}}
{{--                                <div class="col-12 col-md-2 text-left">--}}
{{--                                    <label for="username">نام کاربری</label>--}}
{{--                                </div>--}}

{{--                                <div class="col-12 col-md-7">--}}
{{--                                    {!! Form::text('username', null, array('class' => 'form-control', 'id'=>'username')) !!}--}}
{{--                                </div>--}}

{{--                            </div>--}}

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
                                        <option value="male" {{$user->gender == 'male'? 'selected': ''}}>مرد</option>
                                        <option value="female" {{$user->gender == 'female'? 'selected': ''}}>زن</option>
                                        <option value="other" {{$user->gender == 'other'? 'selected': ''}}>سایر</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="date_of_birth">تاریخ تولد</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <input type="text" class="form-control" id="date_of_birth" value="{{$user->date_of_birth}}" autocomplete="off">
                                    <input type="text" class="form-control" name="date_of_birth" id="alt_date_of_birth" value="{{strtotime($user->date_of_birth)}}" hidden>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-2 text-left">
                                    <label for="city_id">شهر</label>
                                </div>

                                <div class="col-12 col-md-7">
                                    <select class="select_city form-control" name="city_id" id="city_id">
                                        @if($user->city_id)
                                            <option value="{{$user->city_id}}" selected>{{@$user->city->name}}</option>
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

                    @can('address-edit')
                    <div class="tab-pane fade" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

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
                                    <th>عملیات</th>
                                </tr>
                                </thead>

                                <tbody id="addresses_table_body">
                                    @can('address-create')
                                    <tr>
                                        <td></td>
                                        {!! Form::open(['method' => 'POST','route' => ['panel.addresses.store'],'style'=>'display:inline', 'id' => "create_address_form"]) !!}
                                        <td>
                                            <select class="select_city form-control" name="city_id">
                                                @if($user->city_id)
                                                    <option value="{{$user->city_id}}" selected>{{@$user->city->name}}</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            {!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => "نام")) !!}
                                        </td>
                                        <td>
                                            {!! Form::textarea('address', null, array('class' => 'form-control', 'placeholder' => "آدرس کامل")) !!}
                                            {!! Form::text('user_id', $user->id, array('class' => 'form-control','id' => 'user_id', 'hidden')) !!}
                                            {!! Form::text('lat', null, array('class' => 'form-control','id' => 'lat', 'hidden')) !!}
                                            {!! Form::text('lon', null, array('class' => 'form-control','id' => 'lng', 'hidden')) !!}
                                        </td>
                                        {!! Form::close() !!}
                                        <td>
                                            <button type="button" class="btn" title="ثبت" id="create_address_btn"><span class="mdi mdi-plus"></span></button>
                                        </td>
                                    </tr>
                                    @endcan

                                    @foreach($addresses as $address)
                                        <tr>
                                            <td>{{$address->id}}</td>
                                            {!! Form::open(['method' => 'PUT','route' => ['panel.addresses.update', $address->id],'style'=>'display:inline', 'class' => 'update_address', 'onsubmit' => 'return confirm("برای آپدیت مطمئن هستید؟")']) !!}
                                                <td>
                                                    <select class="select_city form-control" name="city_id">
                                                        @if(!is_null($address->city_id))
                                                            <option value="{{$address->city_id}}" selected>{{@$address->city->name}}</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    {!! Form::text('name', $address->name, array('class' => 'form-control')) !!}
                                                </td>
                                                <td>
                                                    {!! Form::textarea('address', $address->address, array('class' => 'form-control')) !!}
                                                </td>
                                            {!! Form::close() !!}
                                            <td>
                                                @can('address-edit')
                                                    <button type="button" class="btn p-0 update_address_btn" title="ویرایش" ><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></button>
                                                @endcan

                                                @can('address-delete')
                                                        {!! Form::open(['method' => 'DELETE','route' => ['panel.addresses.destroy', $address->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای حذف مطمئن هستید؟")']) !!}
                                                        <button type="submit" class="btn p-0" title="حذف"><span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span></button>
                                                        {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endcan

                    @can('order-list')
                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

                            <form method="GET" action="{{route('panel.users.edit', $user->id)}}" id="orders_search_form" class="col-12 row justify-content-start">
                                <div class="search-form d-none d-lg-inline-block">
                                    <div class="input-group">
                                        <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="شماره سفارش ..."
                                               value="{{request('search')}}" autofocus autocomplete="off" />
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" title="جست و جو" type="submit">
                                                <i class="mdi mdi-magnify"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="search-form d-none d-lg-inline-block pl-2">
                                    <div class="input-group">
                                        <select name="status" class="form-control border border-secondary" id="status">
                                            <option value="" @if(is_null(request('status'))) selected @endif>همه</option>
                                            @foreach(array_slice(\App\Models\Order::status_labels(), 1) as $k => $v)
                                                <option value="{{$k}}" @if(request('status') == $k) selected @endif>{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <table class="table text-center font-size-14">
                                <thead>
                                <tr>
                                    <th>شماره سفارش</th>
                                    <th>زمان ثبت</th>
                                    <th>زمان تحویل</th>
                                    <th>نحوه پرداخت</th>
                                    <th>محصولات</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>

                                <tbody id="orders_table">

                                @foreach ($orders as $order)
                                    <tr
                                        @switch($order->status) {{--Deferent Colors Independ of Order Status--}}
                                        @case(\App\Models\Order::status_list()[1])
                                        class="table-danger"
                                        @break

                                        @case(\App\Models\Order::status_list()[2])
                                        class="table-warning"
                                        @break

                                        @case(\App\Models\Order::status_list()[3])
                                        class="table-primary"
                                        @break

                                        @case(\App\Models\Order::status_list()[4])
                                        class="table-success"
                                        @break

                                        @endswitch
                                    >
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($order->created_at)->format('%H:%M - %yy/%m/%d')}}</td>
                                        <td>{{ !is_null($order->delivery_time) ? \Morilog\Jalali\Jalalian::forge($order->delivery_time)->format('%H:%M - %yy/%m/%d') : "-" }}</td>
                                        <td>{{ \App\Models\Order::pay_type_labels($order->pay_type) }}</td>
                                        <td>
                                            <ul class="list-styled text-left">
                                                @if(in_array($order->status, array_slice(\App\Models\Order::status_list(), 2)))
                                                    @foreach($order->products()->get() as $product)
                                                        <li>
                                                            {!! $product->fa_name!!}
                                                            <span class="text-secondary">{!! $product->count . "x "!!}</span>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    @foreach($order->products()->get() as $product)
                                                        <li>
                                                            {!! $product->pivot->fa_name!!}
                                                            <span class="text-secondary">{!! $product->pivot->count . "x "!!}</span>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </td>
                                        <td>{{ \App\Models\Order::status_labels($order->status) }}</td>
                                        <td class="text-left">
                                            @if($order->status != \App\Models\Order::status_list()[0] and $order->status != \App\Models\Order::status_list()[1])
                                                <a class="btn p-0" href="{{ route('panel.orders.show',$order->id) }}" title="نمایش" target="_blank"><span class="mdi mdi-eye-outline mdi-dark mdi-18px"></span></a>
                                            @endif


                                            @can('order-edit')

                                                @if(in_array($order->status, array_slice(\App\Models\Order::status_list(), 0, 2)))
                                                    <a class="btn p-0" href="{{ route('panel.orders.edit',$order->id) }}" title="ویرایش" target="_blank"><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></a>

                                                    <button type="button" class="btn p-0 change-status" data-toggle="modal" data-target="#cancel_order"
                                                            order-Id="{{$order->id}}" order-status="{{\App\Models\Order::status_list()[8]}}"
                                                            title="رد سفارش" >
                                                        <span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span>
                                                    </button>

                                                    {!! Form::open(['method' => 'PATCH','route' => ['panel.orders.change_order_status', $order->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای تایید سفارش مطمئن هستید؟")']) !!}
                                                    <input hidden readonly value="{{\App\Models\Order::status_list()[2]}}" name="status">
                                                    <button type="submit" class="btn p-0" title="تایید سفارش"><span class="mdi mdi-check mdi-dark mdi-18px"></span></button>
                                                    {!! Form::close() !!}
                                                @elseif(in_array($order->status, array_slice(\App\Models\Order::status_list(), 2, 3)))

                                                    <button type="button" class="btn p-0 change-status" data-toggle="modal" data-target="#cancel_order"
                                                            order-Id="{{$order->id}}" order-status="{{\App\Models\Order::status_list()[7]}}"
                                                            title="لغو سفارش">
                                                        <span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span>
                                                    </button>

                                                    {!! Form::open(['method' => 'PATCH','route' => ['panel.orders.next_level', $order->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای انتقال به وضعیت بعدی نمایش داده شده مطمئن هستید؟")']) !!}
                                                    <button type="submit" class="btn p-0" title="{!! "اپدیت به مرحله " . \App\Models\Order::status_labels($order->next_status) !!}"><span class="mdi mdi-rewind-outline mdi-dark mdi-18px"></span></button>
                                                    {!! Form::close() !!}

                                                @endif
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                            <div class="row justify-content-center">
                                {!! $orders->appends([
                                               'search' => request('search'),
                                               'status' => request('status'),
                                               'transaction_page' => $transactions->currentPage(),
                                           ])->render() !!}

                            </div>
                        </div>
                    </div>
                    @endcan

                    @can('transaction-list')
                    <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                        <div class="tab-pane-content mt-5">

                            @include('panel.panel_message')

                            <form method="GET" action="{{route('panel.users.edit', $user->id)}}" id="transactions_search_form">
                                <div class="col-12 row justify-content-between">
                                    <div class="search-form d-none d-lg-inline-block">
                                        <div class="input-group">
                                            <input type="text" id="start_date" name="start_date" class="form-control border border-secondary" placeholder="تاریخ شروع"
                                                   value="{{request('start_date')}}" autofocus autocomplete="off" required/>
                                            <input type="text" class="form-control" name="alt_start_date" id="alt_start_date" value="{{strtotime(request('start_date'))}}" hidden>

                                            <input type="text" id="end_date" name="end_date" class="form-control border border-secondary" placeholder="تاریخ پایان"
                                                   value="{{request('end_date')}}" autofocus autocomplete="off" required/>
                                            <input type="text" class="form-control" name="alt_end_date" id="alt_end_date" value="{{strtotime(request('end_date'))}}" hidden>

                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" title="فیلتر" type="submit" id="search-btn">
                                                    <i class="mdi mdi-filter"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="search-form d-none d-lg-inline-block">
                                        <div class="input-group">
                                            <select name="type" class="form-control border border-secondary" id="type">
                                                <option value="" {{request('type')? '' : 'selected'}}>همه</option>
                                                @foreach(\App\Models\Transaction::transaction_type_labels()  as $k => $v)
                                                    <option value="{{$k}}" @if(request('type') == $k) selected @endif>{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover text-center">
                                <thead>
                                <tr>
                                    <th>شناسه پرداخت</th>
                                    <th>مبلغ(ریال)</th>
                                    <th>وضعیت</th>
                                    <th>توضیحات</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>

                                <tbody>

                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        @if($transaction->transaction_kind == "positive")
                                            <td class="text-success"> {{ number_format($transaction->price) }} + </td>
                                        @else
                                            <td class="text-danger"> {{ number_format($transaction->price) }} - </td>
                                        @endif
                                        <td>{{ \App\Models\Transaction::transaction_type_labels($transaction->type) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ \Morilog\Jalali\Jalalian::forge($transaction->created_at)->format('%H:%M - %Y/%m/%d') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                            <div class="row justify-content-center">
                                {!! $transactions->appends([
                                             'start_date' => request('start_date'),
                                             'alt_start_date' => request('alt_start_date'),
                                             'end_date' => request('end_date'),
                                             'alt_end_date' => request('alt_end_date'),
                                             'type' => request('type'),
                                             'order_page' => $orders->currentPage(),
                                            ])->render() !!}

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

@section('scripts')
    <script src="{{asset('js/persian-date.min.js')}}"></script>
    <script src="{{asset('js/persian-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.js')}}"></script>

    <script src="{{asset('js/custom_js/cities.js')}}"></script>

    <script src="{{asset('js/custom_js/address.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1lgc4S8VAc6DS6K1fuCc53DOHp2jncYs&libraries=places&callback=initialize"></script>


    <div class="modal fade" id="cancel_order" tabindex="-1" role="dialog" aria-labelledby="cancel_order" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle2">علت  </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="popup_form"></div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

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

            $('.update_address_btn').on('click', function () {
                var td = $(this).parent();
                var tr = td.parent();

                var form = tr.find('.update_address');

                form.submit();
            });

            $('#create_address_btn').on('click', function () {
                $('#create_address_form').submit();
            });

            $('#type').on('change', function () {
                $('#transactions_search_form').submit();
            });

            var to, from;
            var initial_start_date = $('#alt_start_date').val();
            var initial_end_date = $('#alt_end_date').val();

            if(initial_start_date){
                to = $("#start_date").pDatepicker({
                    autoClose: true,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_start_date',
                    maxDate: Date.now()
                })
            }else{
                to = $("#start_date").pDatepicker({
                    autoClose: true,
                    initialValue: false,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_start_date',
                    maxDate: Date.now()
                });
            }
            if(initial_end_date){
                from = $("#end_date").pDatepicker({
                    autoClose: true,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD',
                    altField: '#alt_end_date',
                    maxDate: Date.now()
                })
            }else{
                from = $("#end_date").pDatepicker({
                    autoClose: true,
                    initialValue: false,
                    initialValueType: 'gregorian',
                    format: 'YYYY/MM/DD ',
                    altField: '#alt_end_date',
                    maxDate: Date.now()
                });
            }

            $('#status').on('change', function () {
                $('#orders_search_form').submit();
            });

            $('#orders_table').on('click', '.change-status', function () {

                var order_id = $(this).attr('order-id');
                var order_status = $(this).attr('order-status');
                var title = $(this).attr('title');

                $("#exampleModalLongTitle2").html("علت " + title);

                var form_str = '<form method="post" style="display:inline" action="/panel/orders/change_order_status/'+order_id+'">';
                form_str += '@csrf @method("PATCH")';
                form_str += '<div class="form-group row justify-content-center">';
                form_str += '<input hidden readonly value="' + order_status + '"' + ' name="status">';
                form_str += '<textarea class="form-control col-10" name="explanation_of_cancellation"></textarea>';
                form_str += '</div>';
                form_str += '<div class="form-group row justify-content-center">';
                form_str += '<button type="submit" class="ladda-button btn btn-primary btn-default align-content-center">';
                form_str += '<span class="ladda-label">'+ title +'</span>';
                form_str += '<span class="ladda-spinner"></span>';
                form_str += '</button> </div>';
                form_str += '</form>';

                $('#popup_form').html(form_str);
            });

        });
    </script>
@endsection
