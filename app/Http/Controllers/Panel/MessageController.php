<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
//use App\Jobs\RemoveInvalidFcmRegIds;
use App\Models\FCM;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Negar\Smsirlaravel\Smsirlaravel;
use Spatie\Permission\Models\Permission;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $permissions = [
            'message-list',
            'message-create',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }


        $this->middleware('permission:message-list|message-create');
        $this->middleware('permission:message-list', ['only' => ['index','show']]);
        $this->middleware('permission:message-create', ['only' => ['create','store']]);
    }

    public function index(Request $request){
        $query1 = Message::where('is_test', 0)->where('type', 'sms');
        $query2 = Message::where('is_test', 0)->where('type', 'notification');

        //filters
        $keyword = $request->get('search');

        if (!empty($keyword)) {
            $query1->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%$keyword%")
                    ->orWhere('body', 'LIKE', "%$keyword%");
            });
            $query2->where(function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%$keyword%")
                    ->orWhere('body', 'LIKE', "%$keyword%");
            });

        }

        $sms_list = $query1->orderby("id", "desc")
                        ->paginate($this->perPagePanel, ['*'], 'sms');

        $notifications = $query2->orderby("id", "desc")
                            ->paginate($this->perPagePanel, ['*'], 'notification');

        return view('panel.message.index', compact('sms_list', 'notifications'));
    }

    public function show(Request $request, $id){
        $message = Message::find($id);

        if ($message->type == "sms"){
            $query = $message->users();

            //filters
            $keyword = $request->get('search');

            if (!empty($keyword)) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('phone', 'LIKE', "%$keyword%")
                        ->orWhere('email', 'LIKE', "%$keyword%");
                });
            }

        }else {
            $query = $message->devices()->with('user');

            //filters
            $keyword = $request->get('search');

            if (!empty($keyword)) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('phone', 'LIKE', "%$keyword%")
                        ->orWhere('email', 'LIKE', "%$keyword%");
                });
            }
        }

        $data = $query->paginate($this->perPagePanel);

        return view('panel.message.show', compact('data', 'message'));
    }

    public function create(){

        $allUsersCount = User::count();
        $allDevicesCount = FCM::whereNull('admin_id')->count();

        return view('panel.message.create', compact('allUsersCount', 'allDevicesCount'));
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:"sms","notification"',
            'users_id' => 'nullable|array',
            'users_id.*' => Rule::in(User::pluck('id')),
        ]);

        $type = $request->get('type');
        $users = $request->get('users_id');
        $group = $request->get('group');
        $phones = [];
        $tokens = [];

        if ( is_null($users) and $group == null){
            return redirect()->back()->with('error', "هیچ کاربری ای برای ارسال انتخاب نکردید!");
        }

        $message = Message::create([
            'type' => $type,
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'sender_id' => Auth::id(),
        ]);

        if ($type == 'sms'){
            if ($group != null){
                if ($group == "all") {
                    $users_id = User::whereNotNull('phone')->pluck('id')->toArray();
                    $message->users()->attach($users_id);

                    $user_phones = User::whereNotNull('phone')->pluck('phone')->toArray();
                    $phones = $user_phones;
                }
            }else{
                $message->users()->attach($users);
                $phones = User::whereIn('id', $users)->whereNotNull('phone')->pluck('phone')->toArray();
            }

            //dd($phones);
            Smsirlaravel::send($request->get('body'), $phones);

            return redirect('panel/messages')->with('success', 'پیام با موفقیت ارسال شد.');

        }elseif ($type == 'notification'){
            if ($group != null){
                if ($group == "all") {
                    $device_ids = FCM::whereNull('admin_id')->pluck('id')->all();
                    $message->devices()->sync($device_ids);

                    $tokens = FCM::whereNull('admin_id')->pluck('fcm_reg_id')->all();
                }
            }else{
                $user_devices = FCM::whereIn('user_id', $users);
                $device_ids = $user_devices->pluck('id')->all();
                $message->devices()->attach($device_ids);

                $tokens = $user_devices->pluck('fcm_reg_id')->all();
            }

//            dd($tokens);
            $chunked_array = array_chunk($tokens, 1000);

            $failure = 0;

            foreach ($chunked_array as $array){
                try {
                    $json_response =  Message::send_notification($request->get('title'), $request->get('body'), $array);

                    $failure += $json_response->failure;

//                    if ($json_response->failure > 0){
//                        $job = new RemoveInvalidFcmRegIds($array, $json_response->results);
//                        $job->delay(now()->addMinutes(2));
//                        $this->dispatch($job);
//                    }

                } catch (Exception $exception) {
                    return redirect()->back()->with('error', "خطایی در ارسال آگاه ساز رخ داده است.");
                }
            }

            if ($failure > 0){
                return redirect('panel/messages')->with('warning', 'پیام با موفقیت ارسال شد. تعداد ارسال نا موفق:'. $failure);
            }

            return redirect('panel/messages')->with('success', 'پیام با موفقیت ارسال شد.');
        }

        return redirect('panel/messages');
    }

}
