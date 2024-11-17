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
                    مراجعه کنندگان
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست مراجعه کنندگان</h2>

                    @can('user-create')
                        <a href="{{ route('panel.users.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن مراجعه کننده">
                            <i class="mdi mdi-24px mdi-account-plus"></i>
                        </a>
                    @endcan

                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <form method="GET" action="{{url('/panel/users')}}">
                        <div class="search-form d-none d-lg-inline-block col-6">
                            <div class="input-group">
                                <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="نام، نام والدین، موبايل يا ايميل ..."
                                           value="{{request('search')}}" autofocus autocomplete="off" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" title="جست و جو" type="submit">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th></th>
                                <th>نام</th>
                                <th>شماره همراه</th>
                                <th>نام والدین</th>
                                <th>اولین مراجعه</th>
                                <th>تشخیص</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach ($data as $key => $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->parent_name }}</td>
                                    <td>
                                        @if($user->first_visit)
                                            {{ Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($user->first_visit))}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $user->diagnosis }}</td>
                                    <td>

                                        @can('user-edit')
                                        <a class="btn p-0" href="{{ route('panel.users.edit',$user->id) }}" title="ویرایش"><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></a>
                                        @endcan

{{--                                        @can('user-delete')--}}
{{--                                        {!! Form::open(['method' => 'DELETE','route' => ['panel.users.destroy', $user->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای حذف مطمئن هستید؟")']) !!}--}}
{{--                                        <button type="submit" class="btn p-0" title="حذف"><span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span></button>--}}
{{--                                        {!! Form::close() !!}--}}
{{--                                        @endcan--}}

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {!! $data->appends([
                                   'search' => Request::get('search')
                               ])->render() !!}

                </div>
            </div>
        </div>
    </div>
@stop
