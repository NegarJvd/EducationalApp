<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UploadController;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Morilog\Jalali\Jalalian;
use Spatie\Permission\Models\Role;

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
//        $categories_count = Category::count();
//        $videos_count = Video::count();

        return view('panel/home', compact('users_count'));
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
        $last_week_dates = [];

        for($i = 0; $i<7; $i++){
            $start = date("Y-m-d H:i:s", $last_saturday->getTimestamp());
            $end = date("Y-m-d H:i:s", $last_saturday->addHours(23)->addMinutes(59)->addSeconds(59)->getTimestamp());

            $last_week_users[] = User::whereBetween('created_at', [$start, $end])->count();

            $last_week_dates[] = [$start, $end];
            $last_saturday = $last_saturday->addDays(1);
        }

        $this_week_users = [];
        $this_week_dates = [];

        for($i = 0; $i<7; $i++){
            $start = date("Y-m-d H:i:s", $this_saturday->getTimestamp());
            $end = date("Y-m-d H:i:s", $this_saturday->addHours(23)->addMinutes(59)->addSeconds(59)->getTimestamp());

            $this_week_users[] = User::whereBetween('created_at', [$start, $end])->count();

            $this_week_dates[] = [$start, $end];
            $this_saturday = $this_saturday->addDays(1);
        }

        $data['last_week_users'] = $last_week_users;
        $data['last_week_dates'] = $last_week_dates;
        $data['this_week_users'] = $this_week_users;
        $data['this_week_dates'] = $this_week_dates;

        return $this->success($data, "اطلاعات داشبورد");
    }

    public function show_profile(){
        $admin = Auth::user();

        $roles = Role::where('id', '>', 1)->get();
        $adminRole = $admin->roles->pluck('id')->all();

        return view('panel.profile', compact('admin', 'roles', 'adminRole'));
    }

    public function update_profile(Request $request){
        $admin = Auth::user();
        $admin_array = $admin->toArray();

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable','required_if:email,null','numeric','digits:11','regex:/^(09)/', 'unique:users,phone,'. $admin->id],
            'landline_phone' => ['nullable','numeric'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username,'.$admin->id],
            'email' => ['nullable','required_if:phone,null', 'string', 'email', 'max:255', 'unique:users,email,'. $admin->id],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable'],
            'avatar_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', Rule::in(City::pluck('id'))],
        ]);
        $input = $request->all();
        $input['date_of_birth'] = !is_null($request->get('date_of_birth')) ? timestamp_to_date($request->get('date_of_birth')) : $admin_array['date_of_birth'];

        $input = array_merge($admin_array, $input);

        if (is_null($input['avatar_id']) and !is_null($admin_array['avatar_id'])){
            UploadController::delete($admin_array['avatar_id']);
        }

        $admin->update($input);

        return redirect()->back()->with('success', 'اطلاعات شما با موفقیت به روز رسانی شد.');
    }
}
