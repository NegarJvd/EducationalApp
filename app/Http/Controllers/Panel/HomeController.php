<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
//use App\Http\Controllers\UploadController;
use App\Models\Admin;
use App\Models\Content;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Morilog\Jalali\Jalalian;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
//        Artisan::call('storage:link');

        $users_count = User::count();
        $admins_count = Admin::count();
        $contents_count = Content::count();

        return view('panel/home', compact('users_count', 'admins_count', 'contents_count'));
    }

    public function dashboard_info_counts(){
        if(Jalalian::forge('today')->isStartOfWeek()){
            $last_saturday = Jalalian::forge('last saturday');
            $this_saturday = Jalalian::forge('today');
        }else{
            $dayOfWeek = Jalalian::forge('today')->getDayOfWeek();

            $last_saturday = Jalalian::forge('last saturday')->subDays(7);
            $this_saturday = Jalalian::forge('today')->subDays($dayOfWeek);
        }

        $last_week_users = [];
        $last_week_admins = [];
        $last_week_contents = [];
        $last_week_dates = [];

        for($i = 0; $i<7; $i++){
            $start = date("Y-m-d H:i:s", $last_saturday->getTimestamp());
            $end = date("Y-m-d H:i:s", $last_saturday->addHours(23)->addMinutes(59)->addSeconds(59)->getTimestamp());

            $last_week_users[] = User::whereBetween('created_at', [$start, $end])->count();
            $last_week_contents[] = Content::whereBetween('created_at', [$start, $end])->count();
            $last_week_admins[] = Admin::whereBetween('created_at', [$start, $end])->count();

            $last_week_dates[] = [$start, $end];
            $last_saturday = $last_saturday->addDays(1);
        }

        $this_week_users = [];
        $this_week_contents = [];
        $this_week_admins = [];
        $this_week_dates = [];

        for($i = 0; $i<7; $i++){
            $start = date("Y-m-d H:i:s", $this_saturday->getTimestamp());
            $end = date("Y-m-d H:i:s", $this_saturday->addHours(23)->addMinutes(59)->addSeconds(59)->getTimestamp());

            $this_week_users[] = User::whereBetween('created_at', [$start, $end])->count();
            $this_week_contents[] = Content::whereBetween('created_at', [$start, $end])->count();
            $this_week_admins[] = Admin::whereBetween('created_at', [$start, $end])->count();

            $this_week_dates[] = [$start, $end];
            $this_saturday = $this_saturday->addDays(1);
        }

        $data['last_week_users'] = $last_week_users;
        $data['last_week_contents'] = $last_week_contents;
        $data['last_week_admins'] = $last_week_admins;
        $data['last_week_dates'] = $last_week_dates;
        $data['this_week_users'] = $this_week_users;
        $data['this_week_contents'] = $this_week_contents;
        $data['this_week_admins'] = $this_week_admins;
        $data['this_week_dates'] = $this_week_dates;

        return $this->customSuccess($data, "اطلاعات داشبورد");
    }

    public function show_profile(){
        $admin = Auth::user();

        return view('panel.profile', compact('admin'));
    }

    public function update_profile(Request $request){
        $admin = Auth::user();
        $admin_array = $admin->toArray();

        $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'numeric','digits:11','regex:/^(09)/', 'unique:admins,phone,'. $admin->id],
            'email'=> ['nullable', 'string', 'email', 'max:255', 'unique:admins,email,'. $admin->id],
            'medical_system_number', ['nullable', 'unique:admins,medical_system_number,'. $admin->id],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(Admin::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'string', 'max:255'],
            'field_of_profession' => ['nullable', 'string', 'max:255'],
            'resume' => ['nullable', 'string'],
            'degree_of_education' => ['nullable', 'string', 'max:255'],
        ]);
        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date')) : $admin_array['birth_date'];

        $input = array_merge($admin_array, $input);

//        if (is_null($input['avatar_id']) and !is_null($admin_array['avatar_id'])){
//            UploadController::delete($admin_array['avatar_id']);
//        }

        $validation = Validator::make($input, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required'],
            'medical_system_number' => ['required'],
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $admin->update($input);

        return redirect()->back()->with('success', 'اطلاعات شما با موفقیت به روز رسانی شد.');
    }
}
