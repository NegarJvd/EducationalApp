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
                    <a href="{{route('panel.permissions.index')}}">
                        دسترسی ها
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ویرایش دسترسی
                </li>
            </ol>
        </nav>

    </div>

    <div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>ویرایش دسترسی</h2>
        </div>

        <div class="card-body">

            @include('panel.panel_message')

            {!! Form::model($permission, ['method' => 'PATCH','route' => ['panel.permissions.update', $permission->id]]) !!}

            <div class="form-group row">
                <div class="col-12 col-md-2 text-left">
                    <label for="name">نام:</label>
                </div>

                <div class="col-12 col-md-7">
                    {!! Form::text('name', null, array('class' => 'form-control', 'id' => 'name')) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="ladda-button btn btn-primary btn-default align-content-center">
                    <span class="ladda-label">ویرایش</span>
                    <span class="ladda-spinner"></span>
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

@endsection
