@extends('panel.layouts.app')

@section('content')
{{--    @if (session('status'))--}}
{{--        <div class="alert alert-success" role="alert">--}}
{{--            {{ session('status') }}--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    <div>--}}
{{--        <ul>--}}
{{--            @foreach(Route::getRoutes() as $route)--}}
{{--                @if($route->methods()[0] == 'GET' or $route->methods()[0] == 'HEAD')--}}
{{--                    <li><a href="{{$route->uri()}}" target="_blank">{{$route->uri()}}</a></li>--}}
{{--                                {{dd($route->uri())}}--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        </ul>--}}
{{--    </div>--}}

    <div class="row">
        <div class="col-xl-4 col-sm-4">
            <div class="card card-mini mb-4">
                <div class="card-body">
                    <h2 class="mb-1">{{$users_count}}</h2>
                    <p>تعداد کاربران</p>
                    <div class="chartjs-wrapper">
                        <canvas id="users_count"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-4">
            <div class="card card-mini mb-4">
                <div class="card-body">
                    <h2 class="mb-1">0</h2>
                    <p>تعداد دسته بندی ها</p>
                    <div class="chartjs-wrapper">
                        <canvas id="categories_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-4">
            <div class="card card-mini mb-4">
                <div class="card-body">
                    <h2 class="mb-1">0</h2>
                    <p>تعداد ویدیو ها</p>
                    <div class="chartjs-wrapper">
                        <canvas id="videos_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src='{{asset('assets/plugins/charts/Chart.min.js')}}'></script>

    <script src='{{asset('js/custom_js/dashboard.js')}}'></script>
@endsection
