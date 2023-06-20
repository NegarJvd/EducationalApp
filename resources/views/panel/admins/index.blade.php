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
                    مدیران
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست مدیران</h2>

                    @can('admin-create')
                        <a href="{{ route('panel.admins.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن مدیر">
                            <i class="mdi mdi-24px mdi-account-plus"></i>
                        </a>
                    @endcan

{{--                    <a href="#" target="_blank" class="btn btn-outline-primary btn-sm text-uppercase">--}}
{{--                        <i class="mdi mdi-24px  mdi-printer"></i>--}}
{{--                    </a>--}}
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <form method="GET" action="{{url('/panel/admins')}}">
                        <div class="search-form d-none d-lg-inline-block col-6">
                            <div class="input-group">
                                <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="نام، کد نظام پزشکی، موبايل يا ايميل ..."
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
                                <th>کد نظام پزشکی</th>
                                <th>شماره همراه</th>
                                <th>مدرک تحصیلی</th>
                                @can('role-list')
                                    <th>نقش</th>
                                @endcan
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach ($data as $key => $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->medical_system_number }}</td>
                                    <td>{{ $admin->phone }}</td>
                                    <td>{{ $admin->degree_of_education }}</td>
                                    @can('role-list')
                                        <td>
                                            @if(!empty($admin->getRoleNames()))
                                                @foreach($admin->getRoleNames() as $v)
                                                    <label class="badge badge-pill badge-secondary">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>
                                    @endcan
                                    <td>
                                        <a class="btn p-0" href="{{ route('panel.admins.show',$admin->id) }}" title="نمایش"><span class="mdi mdi-eye-outline mdi-dark mdi-18px"></span></a>

                                        @can('admin-edit')
                                        <a class="btn p-0" href="{{ route('panel.admins.edit',$admin->id) }}" title="ویرایش"><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></a>
                                        @endcan

{{--                                        @can('admin-delete')--}}
{{--                                        {!! Form::open(['method' => 'DELETE','route' => ['panel.admins.destroy', $admin->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای حذف مطمئن هستید؟")']) !!}--}}
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
