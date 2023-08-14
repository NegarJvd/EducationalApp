
    <aside class="left-sidebar bg-sidebar">
        <div id="sidebar" class="sidebar">
            <!-- Aplication Brand -->
            <div class="app-brand">
                <div class="row justify-content-center">
                    <a href="{{route('panel')}}" title="EducationalApp Dashboard" class="mr-3 col-8">
{{--                        @include('panel_logo')--}}
                        <span class="brand-name text-truncate">
                              پنل مدیریت
                        </span>
                    </a>
                </div>
            </div>

            <!-- begin sidebar scrollbar -->
            <div class="" data-simplebar style="height: 100%;">
                <!-- sidebar menu -->
                <ul class="nav sidebar-inner" id="sidebar-menu">
                    <li class="has-sub @if(request()->is('panel')) active expand @endif" >
                        <a class="sidenav-item-link" href="{{url('/panel')}}">
                            <i class="mdi mdi-view-dashboard-outline"></i>
                            <span class="nav-text">داشبورد</span>
                        </a>
                    </li>

                    @if(auth()->user()->can('user-list') || auth()->user()->can('role-list') || auth()->user()->can('permission-list'))

                    <li class="has-sub @if(request()->is('panel/users*') or request()->is('panel/admins*') or request()->is('panel/roles*') or request()->is('panel/permissions*')) active expand @endif">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#user"
                           aria-expanded="false" aria-controls="user">
                            <i class="mdi mdi-account-group"></i>
                            <span class="nav-text">مدیریت کاربران</span> <b class="caret"></b>
                        </a>

                        <ul class="collapse " id="user" data-parent="#sidebar-menu">
                            <div class="sub-menu">

                                @can('role-list')
                                    <li class="">
                                        <a class="sidenav-item-link" href="{{url('/panel/roles')}}">
                                            <span class="nav-text">نقش ها</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('admin-list')
                                    <li class="">
                                        <a class="sidenav-item-link" href="{{url('/panel/admins')}}">
                                            <span class="nav-text">مدیران</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('user-list')
                                <li class="">
                                    <a class="sidenav-item-link" href="{{url('/panel/users')}}">
                                        <span class="nav-text">مراجعه کنندگان</span>
                                    </a>
                                </li>
                                @endcan

                            </div>
                        </ul>
                    </li>

                    @endif

                    @can('content-list')
                        <li class="has-sub @if(request()->is('panel/contents*')) active expand @endif">
                            <a class="sidenav-item-link" href="{{url('panel/contents')}}">
                                <i class="mdi mdi-text"></i>
                                <span class="nav-text">مدیریت محتوا</span>
                            </a>
                        </li>
                    @endcan

                    @can('ticket-list')
                        <li class="has-sub @if(request()->is('panel/tickets*')) active expand @endif">
                            <a class="sidenav-item-link" href="{{url('panel/tickets')}}">
                                <i class="mdi mdi-comment-text-multiple"></i>
                                <span class="nav-text">لیست تیکت ها</span>
                            </a>
                        </li>
                    @endcan

                </ul>
            </div>

        </div>
    </aside>
