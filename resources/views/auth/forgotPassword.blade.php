<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="EducationalApp Dashboard">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!--
          HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
        -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src="{{ asset('assets/plugins/nprogress/nprogress.js') }}"></script>

        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
        <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

        <!-- SLEEK CSS -->
        <link href={{asset('assets/plugins/ladda/ladda.min.css')}} rel='stylesheet'>
        <link id="sleek-css" rel="stylesheet" href="{{asset('css/sleek.rtl.css')}}" />

        <!-- FAVICON -->
        <link href="{{asset('assets/img/favicon.ico')}}" rel="shortcut icon" />

    </head>

    <body class="" id="body">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card-body p-5">
                    <h4 class="text-dark mb-5 text-center" id="title_of_page">فراموشی رمز عبور</h4>

                        <div class="row justify-content-center" id="card_body">
                            <div class="form-group col-md-12 mb-4" id="forgot_password_phone">
                                <input id="phone" type="text" class="form-control input-lg "
                                       value="{{ old('phone') }}" autocomplete="phone"
                                       required  autofocus placeholder="شماره موبایل">

                            </div>

                            <div class="form-group col-md-12 mb-4" id="buttons">
                                <div class="d-flex my-2 justify-content-end" id="link">

                                    <div class="d-inline-block mr-3">
                                        <a href="{{url('/login')}}">ورود</a>
                                    </div>

                                </div>

                                <button type="button" class="ladda-button btn btn-lg btn-primary btn-block mb-4 forgot_password">
                                    <span class="ladda-label">بازیابی رمز عبور</span>
                                    <span class="ladda-spinner"></span>
                                </button>

                            </div>
                        </div>

                </div>

            </div>
        </div>
    </div>
        <!-- Javascript -->
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/plugins/ladda/spin.min.js')}}"></script>
        <script src="{{asset('assets/plugins/ladda/ladda.min.js')}}"></script>
        <script src="{{asset('js/sleek.js')}}"></script>

        <script src="{{asset('js/custom_js/resetPassword.js')}}"></script>

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
    </body>
</html>
