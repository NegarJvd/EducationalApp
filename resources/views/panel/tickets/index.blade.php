@extends('panel.layouts.app')

@section('content')
<div class="breadcrumb-wrapper">
    <h1>لیست تیکت ها</h1>
</div>

<div class="card card-default mb-0">
    <div class="row bg-white no-gutters chat">
        <div class="col-lg-5 col-xl-4">
            <!-- Chat Left Side -->
            <div class="chat-left-side">

                <form method="GET" action="{{url('/panel/tickets')}}" class="chat-search p-3">
                    <div class="input-group mb-0">
                        <input type="text" id="search-input" name="search" class="form-control border border-secondary" placeholder="نام، نام والدین، موبايل يا ايميل ..."
                               value="{{request('search')}}" autofocus autocomplete="off" />
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" title="جست و جو" type="submit">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <ul class="list-unstyled border-top mb-0" id="chat-left-content" data-simplebar>

                    @foreach ($data as $key => $user)
                        <li>
                            <a class="media media-message make_active_user" user_id="{{$user->id}}">
                                <div class="position-relative mr-3">
                                    @if(!is_null($user->avatar_id))
                                        <img class="rounded-circle" width="100%" src="{{asset($user->avatar_path())}}" alt="user image">
                                    @else
                                        <img class="rounded-circle" width="100%" src="{{asset('/assets/img/account.png')}}" alt="user image">
                                    @endif
{{--                                    <span class="status away"></span>--}}
                                </div>
                                <div class="media-body d-flex justify-content-between">
                                    <div class="message-contents">
                                        <h4 class="title">{{$user->name}}</h4>
                                        <p class="last-msg">{{$user->latest_ticket->text}}</p>
                                    </div>

                                    <span class="date">{{\Morilog\Jalali\Jalalian::forge($user->tickets_max_created_at)->format('%d %B')}}</span>

                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
        <div class="col-lg-7 col-xl-8">
            <!-- Chats -->
            <div class="chat-right-side">
                <div class="media media-chat align-items-center mb-0 media-chat-header p-2 pr-3 pl-3" href="#" id="header_of_chat">

                    <img id="active_user_image" class="rounded-circle mr-3 p-2" width="100%" src="{{asset('/assets/img/account.png')}}" alt="user image">

                    <div class="media-body w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="heading-title mb-0"><a href="#" id="active_user_name"></a></h3>
                            <h4 class="heading-title mb-0" id="active_user_diagnosis"></h4>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm" title="به روزرسانی لیست" id="refresh">
                                    <i class="mdi mdi-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chat-right-content" id="chat-right-content"></div>

{{--                    <form class="px-5 pb-3" method="POST" action="{{route('panel.tickets.update', $ticket->id)}}">--}}
{{--                        @csrf--}}
{{--                        @method('PUT')--}}
                <div class="input-group p-3">
                    <input type="text" class="form-control" placeholder="پاسخ شما" name="text">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-rotate-180 mdi-send"></i></button>
                    </div>
                </div>
{{--                    </form>--}}

            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{asset('js/custom_js/tickets.js')}}"></script>
@endsection
