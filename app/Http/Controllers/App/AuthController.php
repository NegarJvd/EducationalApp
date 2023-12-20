<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Cryptommer\Smsir\Objects\Parameters;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Cryptommer\Smsir\Smsir;


class AuthController extends Controller
{
    public function login_with_password(Request $request){
        $request->validate([
            'phone' => ['required', 'numeric','digits:11','regex:/^(09)/'],
            'password' => 'required|string',
        ]);

        if (Auth::guard('api')->attempt(['phone' => $request->get('phone'), 'password' => $request->get('password')])) {
            $user = Auth::guard('api')->user();

            $profile = UserResource::make($user)->toJson();
            $success = json_decode($profile, TRUE);
            $success = array_merge($success, ['token' => $user->createToken($user->phone)->plainTextToken]);

            return $this->customSuccess($success, 'کاربر با موفقیت وارد شد.');
        }

        return $this->customError('رمز وارد شده اشتباه است.', 401);
    }

    public function login_first_step(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'numeric','digits:11','regex:/^(09)/'],
        ]);

        $user = User::where('phone', $request->get('phone'))->first();

        $is_first_time = 0;

        if (!$user){
            $user =  User::create([
                'phone' => $request->get('phone'),
            ]);
            $is_first_time = 1;
        }

        $password = "1234";//rand(1111, 9999);
        //Redis::set($user->phone, Hash::make($password), 'EX', 300); //expire in 5 min
        try{
            $send = smsir::Send();
            $parameter = new Parameters('otp', $password);
            $send->Verify($user->phone, $this->sms_template('otp'), [$parameter]);
        }catch(Exception $exception){
            return $this->customError("اعتبار کافی نمی باشد.");
        }

        return $this->customSuccess($is_first_time, 'رمز یکبار مصرف برای ' . $request->get('phone') . ' ارسال شد.');
    }

    public function login_second_step(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'numeric','digits:11','regex:/^(09)/'],
            'otp' => ['required', 'string'],
        ]);

        $right_otp = "1234";

        //$right_otp = Redis::get($request->get('phone'));
        //Redis::del($request->get('phone'));

        //if (Hash::check($request->get('otp'), $right_otp)) {
        if ($request->get('otp') == $right_otp) {
            $user = User::where('phone', $request->get('phone'))->first();

            Auth::login($user);

            $profile = UserResource::make($user)->toJson();
            $success = json_decode($profile, TRUE);
            $success = array_merge($success, ['token' => $user->createToken($user->phone)->plainTextToken]
            );

            return $this->customSuccess($success, "کاربر با موفقیت وارد شد.");
        }

        return $this->customError("رمز وارد شده صحیح نیست. لطفا برای گرفتن رمز جدید اقدام فرمایید.");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->customSuccess('', "کاربر با موفقیت خارج شد.");
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'numeric','digits:11','regex:/^(09)/'],
        ]);

        $user = User::where('phone', $request->get('phone'))->first();

        if (!$user) return $this->customError("کاربر یافت نشد.");

        $verification_code = "1234"; //rand(1111, 9999);
        // Redis::set('verification_' . $user->phone, Hash::make($verification_code), 'EX', 300); //expire in 5 min

        $send = smsir::Send();
        $parameter = new Parameters('otp', $verification_code);
        $send->Verify($user->phone, $this->sms_template('otp'), [$parameter]);

        return $this->customSuccess($request->get('phone'), 'کد تایید برای ' . $request->get('phone') . ' ارسال شد.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'numeric','digits:11','regex:/^(09)/'],
            'verification_code' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        $user = User::where('phone', $request->get('phone'))->first();

        if (!$user) return $this->customError("کاربر یافت نشد.");


        // if (Hash::check($request->get('verification_code'), Redis::get('verification_' . $user->phone))) {
        if ($request->get('verification_code') == "1234") {
            $user->password = Hash::make($request->get('new_password'));
            $user->save();

        } else {
            // Redis::del('verification_' . $user->phone);

            return $this->customError("کد تایید وارد شده صحیح نمی باشد. لطفا برای دریافت مجدد کد تایید اقدام فرمایید.");
        }

        // Redis::del('verification_' . $user->phone);

        return $this->customSuccess(1, "رمز عبور با موفقیت تغییر یافت.");
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        $user = Auth::user();

        if (Hash::check($request->get('old_password'), $user->password)) {
            $user->password = Hash::make($request->get('new_password'));
            $user->save();

            return $this->customSuccess(1, "رمز عبور با موفقیت تغییر یافت.");
        }

        return $this->customError("رمز ورود قبلی، صحیح نیست.");
    }

    public function profile()
    {
        return $this->customSuccess(UserResource::make(Auth::user()), "اطلاعات کاربر");
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:8', 'string'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::in(User::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'first_visit' => ['nullable', 'date'],
            'diagnosis' => ['nullable', 'string', 'max:255']
        ]);

        $data = $request->only(['first_name', 'last_name', 'email', 'birth_date', 'gender', 'address', 'landline_phone', 'father_name', 'mother_name', 'first_visit', 'diagnosis']);
        $old_user = $user->toArray();
        $new_data = array_merge($old_user, $data);

        Validator::make($new_data, [
            'first_name' => ['required'],
            'last_name' => ['required'],
        ])->validate();

        $user->update($new_data);

        if ($request->get('password')){
            $user->password = Hash::make($request->get('password'));
            $user->save();
        }

        return $this->customSuccess(UserResource::make($user), "اطلاعات کابر با موفقیت به روز رسانی شد.");
    }

    public function therapist_info(){
        $user = Auth::user();
        $admin = $user->admin;

        if(!$admin) return $this->customError("درمانگری برای شما اختصاص نیافته است. لطفا برای اختصاص درمانگر، شکیبا باشید.");
        return $this->customSuccess(AdminResource::make($admin), "اطلاعات درمانگر");
    }
}
