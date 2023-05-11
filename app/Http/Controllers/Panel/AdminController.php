<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
//use App\Models\Option;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Negar\Smsirlaravel\Smsirlaravel;
use Spatie\Permission\Models\Permission;
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
        $permissions = [
            'admin-list',
            'admin-create',
            'admin-edit',
            'admin-delete',
            'change_admin_role',
            'search_in_admins_list',
            'change-admin-status',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }

        $this->middleware('permission:admin-list|admin-create|admin-edit|admin-delete|change_admin_role|search_in_admins_list|change-admin-status');
        $this->middleware('permission:admin-list', ['only' => ['index','show']]);
        $this->middleware('permission:admin-create', ['only' => ['create','store']]);
        $this->middleware('permission:admin-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('permission:change_admin_role', ['only' => ['change_admin_role']]);
        $this->middleware('permission:search_in_admins_list', ['only' => ['search_in_admins_list_json']]);
        $this->middleware('permission:change-admin-status', ['only' => ['change_admin_status']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $data = Admin::query();

        $search = $request->get('search');
        if (!is_null($search)){
            $data = $data->where(function ($query) use($search){
                $query->where('name', 'LIKE', "%$search%");
                $query->orWhere('phone' , 'LIKE', "%$search%");
                $query->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $data = $data->orderBy('id','DESC')->paginate($this->perPagePanel);
        return view('panel.admins.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $roles = Role::where('id', '>', 1)->get();

        return view('panel.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins'],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:admins'],
            'landline_phone' => ['nullable','numeric'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:admins'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable'],
            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
            'city_id' => ['nullable', Rule::in(City::pluck('id'))],
            'role' => ['nullable', Rule::in(Role::pluck('name'))]
        ]);

        $input = $request->all();
        $input['date_of_birth'] = !is_null($request->get('date_of_birth')) ? timestamp_to_date($request->get('date_of_birth')) : null;

        $admin = new Admin($input);
        $pass = Str::random(5);
        $admin->password = Hash::make($pass);
        $admin->save();

        $role = $request->get('role');
        if(!is_null($role)){
            $admin->assignRole([$role]);
        }

        Smsirlaravel::ultraFastSend(['password' => $pass], 56161, $admin->phone);

        return redirect()->route('panel.admins.index')
            ->with('success','مدیر با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     *
     * @param Admin $admin
     * @return Application|Factory|View
     */
    public function show(Admin $admin)
    {
        return view('panel.admins.show',compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Admin $admin
     * @return Application|Factory|View
     */
    public function edit(Admin $admin)
    {
        $roles = Role::where('id', '>', 1)->get();
        $adminRole = $admin->roles->pluck('id')->all();

        return view('panel.admins.edit',compact('admin','roles','adminRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username,'.$id],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:admins,phone,'.$id],
            'landline_phone' => ['nullable','numeric'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:admins,email,'.$id],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable'],
            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
            'city_id' => ['nullable', Rule::in(City::pluck('id'))],
        ]);
        $admin = Admin::find($id);

        $input = $request->all();
        $input['date_of_birth'] = !is_null($request->get('date_of_birth')) ? timestamp_to_date($request->get('date_of_birth')) : $admin->date_of_birth;

        $admin->update($input);

        return redirect()->route('panel.admins.index')
            ->with('success','مدیر با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param admin $admin
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('panel.admins.index')
            ->with('success','مدیر با موفقیت حذف شد.');
    }

    public function change_admin_role(Request $request){
        $request->validate([
            'admin_id' => ['required', Rule::in(Admin::pluck('id'))],
            'roles_id' => ['present', 'array'],
            'roles_id.*' => ['nullable', Rule::in(Role::pluck('id'))],
        ]);

        $admin = Admin::find($request->get('admin_id'));

        DB::table('model_has_roles')->where('model_id',$admin->id)->delete();

        $admin->assignRole($request->get('roles_id'));

        return redirect()->route('panel.admins.index')
            ->with('success','نقش مدیر با موفقيت تغيير يافت.');
    }

    public function search_in_admins_list_json(Request $request){
        $query = Admin::select('id', 'name', 'phone', 'email');

        $keyword = $request->get('q');
        if (!empty($keyword)) {
            $query->where(function($query) use($keyword){
                $query->where('name', 'LIKE', "%$keyword%")
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

            if ($status == "active"){
                $admin->status = "inactive";
                $admin->save();
            }else{
                $admin->status = "active";
                $admin->save();
            }

            return redirect()->back()
                ->with('success', "وضعیت مدیر تغییر بافت.");
        }

        return redirect()->back()
            ->with('error', "مدیر یافت نشد.");
    }
}
