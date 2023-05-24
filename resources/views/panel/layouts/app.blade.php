<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="EducationalApp Dashboard">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!--
          HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
        -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
        <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

        <!-- Fonts -->
        <link href="{{asset('css/fontiran.css')}}" rel="stylesheet">
    {{--        <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}


        <!-- PLUGINS CSS STYLE -->
        <link href="{{asset('assets/plugins/simplebar/simplebar.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/nprogress/nprogress.css')}}" rel="stylesheet" />

        <!-- No Extra plugin used -->
        <link href='{{asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css')}}' rel='stylesheet'>
        <link href='{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}' rel='stylesheet'>

{{--        <link href='{{asset('assets/plugins/toastr/toastr.min.css')}}' rel='stylesheet'>  for notification --}}

        <link href={{asset('assets/plugins/ladda/ladda.min.css')}} rel='stylesheet'>

        @yield('head_styles')

        <!-- SLEEK CSS -->
        <link id="sleek-css" rel="stylesheet" href="{{asset('css/sleek.rtl.css')}}" />

        @yield('head_scripts')

        <!-- FAVICON -->
        <link href="{{asset('assets/img/favicon.ico')}}" rel="shortcut icon" />

        <script src="{{ asset('assets/plugins/nprogress/nprogress.js') }}"></script>
    </head>

    <body class="header-fixed sidebar-fixed sidebar-dark header-dark compact-spacing" id="body">

        <script>
            NProgress.configure({ showSpinner: false });
            NProgress.start();
        </script>

{{--        <div id="toaster"></div>--}}

        <div class="wrapper">

            @include('panel.layouts.right_sidebar')

            <div class="page-wrapper">

                @include('panel.layouts.page_wrapper')

                <div class="content-wrapper">
                    <div class="content">

                    @yield('content')

                    </div>
                </div>

                <!-- Footer -->
                    <footer class="footer mt-auto">
                        <div class="copyright bg-white">
                            <p>
                                <span id="copy-year"></span>توسعه یافته توسط جوادزاده
                            </p>
                        </div>
                        <script>
                            var d = new Date();
                            var year = d.getFullYear();
                            document.getElementById("copy-year").innerHTML = year;
                        </script>
                    </footer>
            </div>

        </div>

        <!-- Javascript -->
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/plugins/simplebar/simplebar.min.js')}}"></script>
{{--        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer></script>        --}}
        <script src="{{asset('assets/plugins/ladda/spin.min.js')}}"></script>
        <script src="{{asset('assets/plugins/ladda/ladda.min.js')}}"></script>


        <script src='{{asset('assets/plugins/charts/Chart.min.js')}}'></script>

        <script src='{{asset('js/custom_js/dashboard.js')}}'></script>

{{--        <script src='{{asset('assets/plugins/toastr/toastr.min.js')}}'></script>   for notification--}}

        <script src="{{asset('js/app.js')}}"></script>

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="{{asset('js/custom_js/my_messages_list.js')}}"></script>


        <script>
            /*======== 8. LOADING BUTTON ========*/
            /* 8.1. BIND NORMAL BUTTONS */
            Ladda.bind(".ladda-button", {
                timeout: 5000
            });

            /* 7.2. BIND PROGRESS BUTTONS AND SIMULATE LOADING PROGRESS */
            Ladda.bind(".progress-demo button", {
                callback: function(instance) {
                    var progress = 0;
                    var interval = setInterval(function() {
                        progress = Math.min(progress + Math.random() * 0.1, 1);
                        instance.setProgress(progress);

                        if (progress === 1) {
                            instance.stop();
                            clearInterval(interval);
                        }
                    }, 200);
                }
            });
        </script>


        {{------------------------ TODO Firebase Cloud Notification----------------------------------}}
{{--        <script src="https://www.gstatic.com/firebasejs/8.8.1/firebase-app.js"></script>--}}
{{--        <script src="https://www.gstatic.com/firebasejs/8.8.1/firebase-messaging.js"></script>--}}

{{--        <script>--}}
{{--            $(document).ready(function () {--}}
{{--                var firebaseConfig = {--}}
{{--                    apiKey: "{{env('MIX_FIREBASE_API_KEY')}}",--}}
{{--                    authDomain: "{{env('MIX_FIREBASE_AUTH_DOMAIN')}}",--}}
{{--                    databaseURL: "{{env('MIX_FIREBASE_DATABASE_URL')}}",--}}
{{--                    projectId: "{{env('MIX_FIREBASE_PROJECT_ID')}}",--}}
{{--                    storageBucket: "{{env('MIX_FIREBASE_STORAGE_BUCKET')}}",--}}
{{--                    messagingSenderId: "{{env('MIX_FIREBASE_SENDER_ID')}}",--}}
{{--                    appId: "{{env('MIX_FIREBASE_APP_ID')}}",--}}
{{--                    measurementId: "{{env('MIX_FIREBASE_MEASUREMENT_ID')}}",--}}
{{--                };--}}

{{--                // Initialize Firebase--}}
{{--                firebase.initializeApp(firebaseConfig);--}}

{{--                const messaging = firebase.messaging();--}}

{{--                function initFirebaseMessagingRegistration() {--}}
{{--                    messaging.requestPermission().then(function () {--}}
{{--                        return messaging.getToken()--}}
{{--                    }).then(function(token) {--}}
{{--                        $.ajax({--}}
{{--                            url: '/panel/store_fcm',--}}
{{--                            type: 'POST',--}}
{{--                            async: true,--}}
{{--                            dataType: 'json',--}}
{{--                            data : {--}}
{{--                                'fcm_reg_id' : token,--}}
{{--                            },--}}
{{--                            success: function (data, textStatus, jQxhr) {--}}
{{--                                console.log(data);--}}
{{--                            },--}}
{{--                            error: function (data, textStatus, errorThrown) {--}}
{{--                                console.log(data);--}}
{{--                            },--}}
{{--                            headers: {--}}
{{--                                'Accept': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                            }--}}
{{--                        });--}}

{{--                    }).catch(function (err) {--}}
{{--                        console.log(`Token Error :: ${err}`);--}}
{{--                    });--}}
{{--                }--}}

{{--                initFirebaseMessagingRegistration();--}}

{{--                messaging.onMessage(function (payload) {--}}
{{--                    const title = payload.notification.title;--}}
{{--                    const options = {--}}
{{--                        body: payload.notification.body,--}}
{{--                        icon: payload.notification.icon,--}}
{{--                    };--}}
{{--                    new Notification(title, options);--}}
{{--                });--}}

{{--            });--}}
{{--        </script>--}}

        @yield('scripts')
    </body>
</html>
