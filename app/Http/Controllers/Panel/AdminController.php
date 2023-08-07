<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
//use Negar\Smsirlaravel\Smsirlaravel;
use Spatie\Permission\Models\Role;
use App\Models\admin;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:admin-list|admin-create|admin-edit|admin-delete|change_admin_role|search_in_admins_list|change-admin-status');
        $this->middleware('permission:admin-list', ['only' => ['index','show']]);
        $this->middleware('permission:admin-create', ['only' => ['create','store']]);
        $this->middleware('permission:admin-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('permission:change_admin_role', ['only' => ['change_admin_role']]);
        $this->middleware('permission:search_in_admins_list', ['only' => ['search_in_admins_list_json']]);
        $this->middleware('permission:change-admin-status', ['only' => ['change_admin_status']]);
    }

    public function index(Request $request)
    {
        $data = Admin::query();

        $search = $request->get('search');
        if (!is_null($search)){
            $data = $data->where(function ($query) use($search){
                $query->where('first_name', 'LIKE', "%$search%");
                $query->orWhere('last_name' , 'LIKE', "%$search%");
                $query->orWhere('medical_system_number' , 'LIKE', "%$search%");
                $query->orWhere('phone' , 'LIKE', "%$search%");
                $query->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $data = $data->orderBy('id','DESC')->paginate($this->perPagePanel);
        return view('panel.admins.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $roles = Role::where('id', '>', 1)->get();

        return view('panel.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric','digits:11','regex:/^(09)/', 'unique:admins,phone'],
            'email'=> ['nullable', 'string', 'email', 'max:255', 'unique:admins,email'],
            'medical_system_number' => ['required', 'unique:admins,medical_system_number'],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(Admin::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric'],
            'field_of_profession' => ['nullable', 'string', 'max:255'],
            'resume' => ['nullable', 'string'],
            'degree_of_education' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', Rule::in(Role::pluck('name'))]
        ]);

        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date'), "Y-m-d") : null;
        $input['status'] = Admin::status()[1];

        DB::beginTransaction();
        try {
            $admin = new Admin($input);
            $pass = Str::random(5);
            $admin->password = Hash::make($pass);
            $admin->save();

            $role = $request->get('role');
            if(!is_null($role)){
                $admin->assignRole([$role]);
            }

//        Smsirlaravel::ultraFastSend(['password' => $pass], 56161, $admin->phone);

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();

            return redirect()->back()
                ->with('error','مشکلی در ایجاد مدیر پیش آمده است لطفا مجدد اطلاعات را وارد کنید.');
        }

        return redirect()->route('panel.admins.index')
            ->with('success','مدیر با موفقیت ایجاد شد.');
    }

    public function show(Admin $admin)
    {
        return view('panel.admins.show',compact('admin'));
    }

    public function edit(Admin $admin)
    {
        $roles = Role::where('id', '>', 1)->get();
        $adminRole = $admin->roles->pluck('id')->all();

        return view('panel.admins.edit',compact('admin','roles','adminRole'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'numeric','digits:11','regex:/^(09)/', 'unique:admins,phone,'.$id],
            'email'=> ['nullable', 'string', 'email', 'max:255', 'unique:admins,email,'.$id],
            'medical_system_number' => ['nullable', 'unique:admins,medical_system_number'],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(Admin::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric'],
            'field_of_profession' => ['nullable', 'string', 'max:255'],
            'resume' => ['nullable', 'string'],
            'degree_of_education' => ['nullable', 'string', 'max:255'],
//            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
        ]);

        $admin = Admin::find($id);
        $admin_array = $admin->toArray();

        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date')) : $admin->birth_date;

        $input = array_merge($admin_array, $input);

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

        return redirect()->back()->with('success','مدیر با موفقیت ویرایش شد.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('panel.admins.index')
            ->with('success','مدیر با موفقیت حذف شد.');
    }

    public function change_admin_role(Request $request){
        $request->validate([
            'admin_id' => ['required', Rule::in(Admin::pluck('id'))],
//            'roles_id' => ['present', 'array'],
            'roles_id' => ['required', Rule::in(Role::pluck('id'))],
        ]);

        $admin = Admin::find($request->get('admin_id'));

        DB::table('model_has_roles')->where('model_id',$admin->id)->delete();

        $admin->assignRole([$request->get('roles_id')]);

        return redirect()->back()
            ->with('success','نقش مدیر با موفقيت تغيير يافت.');
    }

    public function search_in_admins_list_json(Request $request){
        $query = Admin::select('id', 'first_name', 'last_name', 'medical_system_number', 'phone', 'email');

        $keyword = $request->get('q');
        if (!empty($keyword)) {
            $query->where(function($query) use($keyword){
                $query->where('first_name', 'LIKE', "%$keyword%")
                    ->orWhere('last_name', 'LIKE', "%$keyword%")
                    ->orWhere('medical_system_number', 'LIKE', "%$keyword%")
                    ->orWhere('phone', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%");
            });
        }

        $admins = $query->paginate($this->perPagePanel);
        return response($admins);
    }

    public function change_admin_status($admin_id){
        $admin = Admin::find($admin_id);
        if ($admin){
            $status = $admin->status;

            if ($status == Admin::status()[0]){
                $admin->status = Admin::status()[1];
                $admin->save();
            }else{
                $admin->status = Admin::status()[0];
                $admin->save();
            }

            return redirect()->back()
                ->with('success', "وضعیت مدیر تغییر بافت.");
        }

        return redirect()->back()
            ->with('error', "مدیر یافت نشد.");
    }
}
