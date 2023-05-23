<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
//use Negar\Smsirlaravel\Smsirlaravel;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return "phone";
    }

    public function forgotPasswordView(){
        return view('auth.forgotPassword');
    }

    public function forgotPassword(Request $request){
        $request->validate([
            'phone' => 'required|numeric|digits:11|regex:/^(09)/',
        ]);

        $admin = Admin::where('phone', $request->get('phone'))->first();

        if(!$admin) return $this->customError('شماره همراه اشتباه است.');

        $token = rand(11111, 99999);

        Redis::set('reset_password_token_' . $request->get('phone'), Hash::make($token), 'EX', 300); //expire in 5 min

//        Smsirlaravel::ultraFastSend(['password' => $token], 56898, $admin->phone); //TODO

        return $this->success('', 'کد تایید به شماره ' . $admin->phone . ' پیامک شد.');
    }

    public function resetPassword(Request $request){
        $request->validate([
            'phone' => 'required|numeric|digits:11|regex:/^(09)/',
            'token' => 'required|string',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $admin = Admin::where('phone', $request->get('phone'))->first();

        if(!$admin) return $this->customError('نام کاربری اشتباه است.');

        $right_token = Redis::get('reset_password_token_' . $request->get('phone'));

        if(Hash::check($request->get('token'), $right_token)){
            $admin->password = Hash::make($request->get('password'));
            $admin->save();
        }else{
            return $this->customError("کد تایید وارد شده اشتباه است.");
        }

        Redis::del('reset_password_token_' . $request->get('phone'));

        return $this->success('', "رمز شما با موفقیت تغییر یافت.");
    }
}
