<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
//use Negar\Smsirlaravel\Smsirlaravel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete|search_in_users_list|change-user-status');
        $this->middleware('permission:user-list', ['only' => ['index','show']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:search_in_users_list', ['only' => ['search_in_users_list_json']]);
    }

    public function index(Request $request)
    {
        $data = User::query();

        $search = $request->get('search');
        if (!is_null($search)){
            $data = $data->where(function ($query) use($search){
                $query->where('first_name', 'LIKE', "%$search%");
                $query->orWhere('last_name' , 'LIKE', "%$search%");
                $query->orWhere('phone' , 'LIKE', "%$search%");
                $query->orWhere('email', 'LIKE', "%$search%");
                $query->orWhere('father_name' , 'LIKE', "%$search%");
                $query->orWhere('mother_name', 'LIKE', "%$search%");

            });
        }

        $data = $data->orderBy('id','DESC')->paginate($this->perPagePanel);
        return view('panel.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('panel.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(User::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric'],
            'father_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'first_visit' => ['nullable'],
            'diagnosis' => ['nullable', 'string', 'max:255'],
            'admin_id' => ['nullable', Rule::in(Admin::pluck('id'))],
//            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
        ]);

        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date'), "Y-m-d") : null;
        $input['first_visit'] = !is_null($request->get('first_visit')) ? timestamp_to_date($request->get('first_visit'), "Y-m-d") : null;

        DB::beginTransaction();

        try {
            $user = new User($input);
            $pass = Str::random(5);
            $user->password = Hash::make($pass);
            $user->save();

//            Smsirlaravel::ultraFastSend(['password' => $pass], 56161, $user->phone);

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();

            return redirect()->back()
                ->with('error','مشکلی در ایجاد مراجعه کننده پیش آمده است لطفا مجدد اطلاعات را وارد کنید.');
        }

        return redirect()->route('panel.users.index')
            ->with('success','مراجعه کننده با موفقیت ایجاد شد.');
    }

    public function edit(User $user)
    {
        if($user->admin_id == Auth::id()){
            $clusters = $user->clusters()
                ->paginate()
                ->unique('content_id');

            return view('panel.users.edit',compact('user', 'clusters'));
        }

        return view('panel.users.edit',compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable','numeric','digits:11','regex:/^(09)/', 'unique:users,phone,'. $id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'. $id],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(User::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'first_visit' => ['nullable'],
            'diagnosis' => ['nullable', 'string', 'max:255'],
            'admin_id' => ['nullable', Rule::in(Admin::pluck('id'))],

        ]);
        $user = User::find($id);
        $user_array = $user->toArray();

        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date')) : $user->birth_date;
        $input['first_visit'] = !is_null($request->get('first_visit')) ? timestamp_to_date($request->get('first_visit'), "Y-m-d") : null;

        $input = array_merge($user_array, $input);

        $validation = Validator::make($input, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required'],
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $user->update($input);

        return redirect()->back()->with('success','مراجعه کننده با موفقیت ویرایش شد.');
    }

//    public function destroy(User $user)
//    {
//        $user->delete();
//        return redirect()->route('panel.users.index')
//            ->with('success','مراجعه کننده با موفقیت حذف شد.');
//    }

//    public function search_in_users_list_json(Request $request){
//        $query = User::select('id', 'first_name', 'last_name', 'phone', 'email', 'father_name', 'mother_name');
//
//        $keyword = $request->get('q');
//        if (!empty($keyword)) {
//            $query->where(function($query) use($keyword){
//                $query->where('first_name', 'LIKE', "%$keyword%")
//                        ->orWhere('last_name', 'LIKE', "%$keyword%")
//                        ->orWhere('phone', 'LIKE', "%$keyword%")
//                        ->orWhere('email', 'LIKE', "%$keyword%")
//                        ->orWhere('father_name', 'LIKE', "%$keyword%")
//                        ->orWhere('mother_name', 'LIKE', "%$keyword%");
//            });
//        }
//
//        $users = $query->paginate($this->perPagePanel);
//        return response($users);
//    }

}
