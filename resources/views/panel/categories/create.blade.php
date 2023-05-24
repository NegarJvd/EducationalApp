@extends('panel.layouts.app')


@section('content')
    <div class="breadcrumb-wrapper">
        <h1>مدیریت محصولات</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/panel')}}">
                        <span class="mdi mdi-home"></span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('panel.categories.index')}}">
                       دسته بندی ها
                    </a>
                </li>
                <li class="breadcrumb-item">
                    ایجاد دسته بندی
                </li>
            </ol>
        </nav>

    </div>

    <div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>ایجاد دسته بندی</h2>
        </div>

        <div class="card-body">

            @include('panel.panel_message')

            {!! Form::open(array('route' => 'panel.categories.store','method'=>'POST')) !!}

            <div class="form-group row">
                <div class="col-12 col-md-2 text-left">
                    <label for="fa_name">نام فارسی:</label>
                </div>

                <div class="col-12 col-md-7">
                    {!! Form::text('fa_name', null, array('class' => 'form-control', 'id' => 'fa_name')) !!}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-12 col-md-2 text-left">
                    <label for="en_name">نام انگلیسی:</label>
                </div>

                <div class="col-12 col-md-7">
                    {!! Form::text('en_name', null, array('class' => 'form-control', 'id' => 'en_name')) !!}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-12 col-md-2 text-left">
                    <label for="discount">تخفیف:</label>
                </div>

                <div class="col-12 col-md-7">
                    <div class="input-group">
                        {!! Form::number('discount', 0, array('class' => 'form-control', 'id' => 'discount', 'max' => 100)) !!}
                        <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupAppend">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="ladda-button btn btn-primary btn-default align-content-center">
                    <span class="ladda-label">ذخیره</span>
                    <span class="ladda-spinner"></span>
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

@endsection
