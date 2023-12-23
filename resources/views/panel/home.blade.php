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
                        <canvas class="users_count"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-4">
            <div class="card card-mini mb-4">
                <div class="card-body">
                    <h2 class="mb-1">20</h2>
                    <p>تعداد محتوا ها</p>
                    <div class="chartjs-wrapper">
                        <canvas class="users_count"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-4">
            <div class="card card-mini mb-4">
                <div class="card-body">
                    <h2 class="mb-1">11</h2>
                    <p>تعداد مديران</p>
                    <div class="chartjs-wrapper">
                        <canvas class="users_count"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-4 text-primary">راهنمای درمانگر</h4>
                    <li>
                        به دلیل فیلترینگ، ممکن است برخی آیکون‌ها یا صفحه نمایش سایت پنل کامل نمایش داده نشود که در این صورت می بایست یک بار فیلترشکن روشن شود؛ در این صورت، مشکل آیکون‌ها به طور کامل رفع خواهد شد.
                    </li>
                    <li>
                        درمانگر می‌تواند از قسمت مدیریت کاربران، دسترسی لازم به نوع فعالیت و با جنسیت مدل مدنظر را انتخاب نماید تا به مراجعین نمایش داده شود و قفل مربوط به آن در اپلیکیشن باز گردد.
                    </li>
                    <li>
                        درمانگر می‌تواند اطلاعات مراجعه کننده‌ی خود را در قسمت مراجعه کنندگان وارد نماید.
                    </li>
                    <li>
                        درمانگر می‌تواند در صورت نیاز، با ارتباط گرفتن با مدیران از طریق آدرس ایمیل تعبیه شده <b>(Samane_zohrabi@yahoo.com)</b>، و با اجازه‌ی مدیران و دادن دسترسی به آن‌ها، ویدیوهای جدیدی را برای مراجع خاص اضافه نماید.
                    </li>
                    <li>
                        درمانگر می‌بایست قبل از استفاده از پنل، اطلاعات فردی خود را در سایت ثبت و مدارک لازم را بارگذاری نماید.
                    </li>
                    <li>
                        محتوای پیام‌های رد و بدل شده بین مراجع و درمانگر مربوطه برای مدیران و سایر درمانگران قابل مشاهده نخواهد بود.
                    </li>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src='{{asset('assets/plugins/charts/Chart.min.js')}}'></script>
    <script src="{{asset('js/chart.js')}}"></script>
    <script src='{{asset('js/custom_js/dashboard.js')}}'></script>
@endsection
