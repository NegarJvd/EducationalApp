@extends('panel.layouts.app')

@section('content')

    <div class="breadcrumb-wrapper">
        <h1>مدیریت محتوا</h1>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-0">
                <li class="breadcrumb-item">
                    <a href="{{url('/panel')}}">
                        <span class="mdi mdi-home"></span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    محتوا ها
                </li>
            </ol>
        </nav>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>لیست محتوا ها</h2>

                    @can('content-create')
                        <a href="{{ route('panel.contents.create') }}" class="btn btn-outline-primary btn-sm text-uppercase" title="افزودن محتوا">
                            <i class="mdi mdi-24px mdi-note-plus"></i>
                        </a>
                    @endcan
                </div>

                <div class="card-body">

                    @include('panel.panel_message')

                    <div class="row">

                        <form method="GET" action="{{url('/panel/contents')}}" id="search_form" class="col-12 row justify-content-between">
                            <div class="search-form d-none d-lg-inline-block col-4">
                                <div class="input-group">
                                    <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="عنوان ..."
                                           value="{{request('search')}}" autofocus autocomplete="off" />
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" title="جست و جو" type="submit">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @foreach($contents as $content)
                            <div class="col-md-6 col-xl-3">
                                <div class="card  mb-4 p-0">
                                    <h5 class="card-title text-primary pt-4 pb-2 px-3">
                                        <a href="{{ route('panel.contents.edit',$content->id) }}">
                                            {{$content->name}}
                                        </a>
                                    </h5>

                                    <img class="card-img rounded-0" src="{{$content->cover_image}}">

                                    <div class="card-body row">
                                        <div class="col-10">
                                            <p class="card-text pb-2">
                                                تعداد دسته بندی ها: {{$content->clusters_count}}
                                            </p>
                                            <p class="card-text pb-2">
                                                تعداد کاربران فعال: {{$content->users_count}}
                                            </p>

                                        </div>

                                        <div class="col-2 justify-content-center d-flex align-items-end">
                                            @can('content-delete')
                                                {!! Form::open(['method' => 'DELETE','route' => ['panel.contents.destroy', $content->id], 'onsubmit' => 'return confirm("با حدف محتوا، تمام فایل ها و رکورد های ثبت شده توسط مراجعه کنندگان حذف خواهند شد. برای این کار مطمئن هستید؟")']) !!}
                                                <button type="submit" class="btn btn-sm btn-outline-primary float-right" title="حذف"><span class="mdi mdi-trash-can"></span></button>
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {!! $contents->appends([
                                   'search' => \Request::get('search')
                               ])->render() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
