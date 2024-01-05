
        <!-- Header -->
        <header class="main-header " id="header">
            <nav class="navbar navbar-static-top navbar-expand-lg">
                <!-- Sidebar toggle button -->
                <button id="sidebar-toggler" class="sidebar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <!-- search form -->
                <div class="search-form d-none d-lg-inline-block">
{{--                    <div class="input-group">--}}
{{--                        <button type="button" name="search" id="search-btn" class="btn btn-flat">--}}
{{--                            <i class="mdi mdi-magnify"></i>--}}
{{--                        </button>--}}
{{--                        <input type="text" name="query" id="search-input" class="form-control" placeholder="جست و جو"--}}
{{--                               autofocus autocomplete="off" />--}}
{{--                    </div>--}}
{{--                    <div id="search-results-container">--}}
{{--                        <ul id="search-results"></ul>--}}
{{--                    </div>--}}
                </div>

                <div class="navbar-right ">
                    <ul class="nav navbar-nav">
                        {{--
                        <li class="dropdown notifications-menu custom-dropdown">
                            <button class="dropdown-toggle notify-toggler custom-dropdown-toggler messages_list">
                                <i class="mdi mdi-bell-outline"></i>
                            </button>

                            <div class="card card-default dropdown-notify dropdown-menu-right mb-0">
                                <div class="card-header card-header-border-bottom px-3 justify-content-between">
                                    <h2>پیام های من</h2>
                                    <a class="btn btn-outline-primary btn-sm text-uppercase messages_list" title="به روزرسانی لیست">
                                        <i class="mdi mdi-refresh"></i>
                                    </a>
                                </div>

                                <div class="card-body px-0 py-3">
                                    <ul class="nav nav-tabs nav-style-border p-0 justify-content-between" id="myTab" role="tablist">
                                        <li class="nav-item mx-3 my-0 py-0">
                                            <a class="nav-link active pb-3" id="my-sms-tab" data-toggle="tab" href="#my-sms" role="tab" aria-controls="my-sms" aria-selected="true">پیامک ها</a>
                                        </li>

                                        <li class="nav-item mx-3 my-0 py-0">
                                            <a class="nav-link pb-3" id="my-notification-tab" data-toggle="tab" href="#my-notification" role="tab" aria-controls="my-notification" aria-selected="false">آگاه ساز ها</a>
                                        </li>

                                        <li class="nav-item mx-3 my-0 py-0">
                                            <a class="nav-link pb-3" id="my-ticket-tab" data-toggle="tab" href="#my-ticket" role="tab" aria-controls="my-ticket" aria-selected="false">تیکت ها</a>
                                        </li>

                                    </ul>

                                    <div class="tab-content" id="myTabContent3">
                                        <div class="tab-pane fade show active" id="my-sms" role="tabpanel" aria-labelledby="my-sms-tab">
                                            <ul class="list-unstyled my_messages_list_content" style="height: 360px" id="sms_list_content"></ul>
                                        </div>

                                        <div class="tab-pane fade" id="my-notification" role="tabpanel" aria-labelledby="my-notification-tab">
                                            <ul class="list-unstyled my_messages_list_content" style="height: 360px" id="notification_list_content"></ul>
                                        </div>

                                        <div class="tab-pane fade" id="my-ticket" role="tabpanel" aria-labelledby="my-ticket-tab">
                                            <ul class="list-unstyled my_messages_list_content" style="height: 360px" id="ticket_list_content"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        --}}
{{--                        <li class="right-sidebar-in right-sidebar-2-menu">--}}
{{--                            <i class="mdi mdi-settings mdi-spin"></i>--}}
{{--                        </li>--}}

                        <!-- User Account -->
                        <li class="dropdown user-menu">
                            <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" data-target="#dropdown-menu">

                                @if(auth()->user()->avatar_id)
                                    <img src="{{asset(auth()->user()->avatar_path())}}" class="user-image rounded-circle" alt="User Image" />
                                @else
                                    <img src="{{asset('/assets/img/user.png')}}" class="user-image rounded-circle" alt="User Image" />
                                @endif

                                <span class="d-none d-lg-inline-block">{{auth()->user()->name}}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" id="dropdown-menu">
                                <!-- User image -->
                                <li class="dropdown-header">
                                    <div class="d-inline-block">
                                        {{auth()->user()->name}}<small class="pt-1">{{auth()->user()->email}}</small><small class="pt-1">{{auth()->user()->phone}}</small>
                                    </div>
                                </li>

                                <li>
                                    <a href="{{url('panel/profile')}}">
                                        <i class="mdi mdi-account"></i> پروفایل من
                                    </a>
                                </li>

                                <li class="dropdown-footer">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"> <i class="mdi mdi-logout"></i> خروج </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
