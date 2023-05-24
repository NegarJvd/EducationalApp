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
                    دسترسی ها
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست دسترسی ها</h2>

                    @can('permission-create')
                        <a href="{{ route('panel.permissions.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن دسترسی">
                            <i class="mdi mdi-24px mdi-account-plus"></i>
                        </a>
                    @endcan
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <form method="GET" action="{{route('panel.permissions.index')}}">
                        <div class="search-form d-none d-lg-inline-block">
                            <div class="input-group">
                                <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="نام ..."
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
                                <th>عملیات</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        @can('permission-edit')
                                            <a class="btn p-0" href="{{ route('panel.permissions.edit',$permission->id) }}" title="ویرایش"><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></a>
                                        @endcan
{{--                                        @can('permission-delete')--}}
{{--                                            {!! Form::open(['method' => 'DELETE','route' => ['panel.permissions.destroy', $permission->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای حذف مطمئن هستید؟")']) !!}--}}
{{--                                                <button type="submit" class="btn p-0" title="حذف"><span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span></button>--}}
{{--                                            {!! Form::close() !!}--}}
{{--                                        @endcan--}}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {!! $permissions->appends([
                                   'search' => Request::get('search')
                               ])->render() !!}

                </div>
            </div>
        </div>
    </div>

@endsection
