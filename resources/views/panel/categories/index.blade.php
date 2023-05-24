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
                    دسته بندی ها
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست دسته بندی ها</h2>

                    @can('category-create')
                        <a href="{{ route('panel.categories.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن دسته بندی">
                            <i class="mdi mdi-24px mdi-playlist-plus"></i>
                        </a>
                    @endcan
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <form method="GET" action="{{url('/panel/categories')}}" id="search_form">
                        <div class="search-form d-none d-lg-inline-block">
                            <div class="input-group">
                                <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="نام فارسی یا انگلیسی..."
                                           value="{{request('search')}}" autofocus autocomplete="off"/>
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
                                <th>نام فارسی</th>
                                <th>نام انگلیسی</th>
                                <th>تخفیف (%)</th>
                                <th>تعداد محصولات</th>
                                <th>عملیات</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->fa_name }}</td>
                                    <td>{{ $category->en_name }}</td>
                                    <td>{{ $category->discount }}</td>
                                    <td>{{ $category->products()->count() }}</td>

                                    <td>
                                        @can('category-edit')
                                            <a class="btn p-0" href="{{ route('panel.categories.edit',$category->id) }}" title="ویرایش"><span class="mdi mdi-square-edit-outline mdi-dark mdi-18px"></span></a>
                                        @endcan
                                        @can('category-delete')
                                            @if($category->products()->count() == 0)
                                            {!! Form::open(['method' => 'DELETE','route' => ['panel.categories.destroy', $category->id],'style'=>'display:inline', 'onsubmit' => 'return confirm("برای حذف مطمئن هستید؟")']) !!}
                                            <button type="submit" class="btn p-0" title="حذف"><span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span></button>
                                            {!! Form::close() !!}
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                    </table>

                    {!! $categories->appends([
                                   'search' => Request::get('search')
                               ])->render() !!}

                </div>
            </div>
        </div>
    </div>
@stop
