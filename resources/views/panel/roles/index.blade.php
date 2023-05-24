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
                    نقش ها
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست نقش ها</h2>

                    @can('role-create')
                        <a href="{{ route('panel.roles.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن نقش">
                            <i class="mdi mdi-24px mdi-account-plus"></i>
                        </a>
                    @endcan
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <form method="GET" action="{{route('panel.roles.index')}}">
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

                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @can('role-edit')
                                            <a class="btn p-0" href="{{ route('panel.roles.edit',$role->id) }}" title="ویرایش"><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></a>
                                        @endcan
{{--                                        @can('role-delete')--}}
{{--                                            {!! Form::open(['method' => 'DELETE','route' => ['panel.roles.destroy', $role->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای حذف مطمئن هستید؟")']) !!}--}}
{{--                                                <button type="submit" class="btn p-0" title="حذف"><span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span></button>--}}
{{--                                            {!! Form::close() !!}--}}
{{--                                        @endcan--}}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    {!! $roles->appends([
                                   'search' => Request::get('search')
                               ])->render() !!}

                </div>
            </div>
        </div>
    </div>
@stop
