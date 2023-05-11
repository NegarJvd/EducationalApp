<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
//use App\Models\Option;
use App\Models\Order;
use App\Models\Upload;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Negar\Smsirlaravel\Smsirlaravel;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'search_in_users_list',
            'change-user-status',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }

        $this->middleware('permission:user-list|user-create|user-edit|user-delete|search_in_users_list|change-user-status');
        $this->middleware('permission:user-list', ['only' => ['index','show']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
        $this->middleware('permission:search_in_users_list', ['only' => ['search_in_users_list_json']]);
        $this->middleware('permission:change-user-status', ['only' => ['change_user_status']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $data = User::query();

        $search = $request->get('search');
        if (!is_null($search)){
            $data = $data->where(function ($query) use($search){
                $query->where('name', 'LIKE', "%$search%");
                $query->orWhere('phone' , 'LIKE', "%$search%");
                $query->orWhere('email', 'LIKE', "%$search%");
            });
        }

        $data = $data->orderBy('id','DESC')->paginate($this->perPagePanel);
        return view('panel.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('panel.users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['nullable', 'string', 'max:255'],
//            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:users'],
            'landline_phone' => ['nullable','numeric'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable'],
            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
            'city_id' => ['nullable', Rule::in(City::pluck('id'))],
        ]);

        $input = $request->all();
        $input['date_of_birth'] = !is_null($request->get('date_of_birth')) ? timestamp_to_date($request->get('date_of_birth')) : null;

        $user = new User($input);
        $pass = Str::random(5);
        $user->password = Hash::make($pass);
        $user->save();

        Smsirlaravel::ultraFastSend(['password' => $pass], 56161, $user->phone);

        if($request->wantsJson()){
            return $this->success(User::with('addresses')->find($user->id), "کاربر جدید با موفقیت اضافه شد.");
        }

        return redirect()->route('panel.users.index')
            ->with('success','کاربر با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        $addresses = $user->addresses()->get();
        return view('panel.users.show',compact('user', 'addresses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function edit(User $user, Request $request)
    {
        $addresses = $user->addresses()->get();

        //these are can be in show function------------------------
        $orders = $user->orders()
            ->where('status', '!=', Order::status_list()[0]);

        //search and filter
        $search = $request->get('search');
        if (!is_null($search)){
            $orders->where('order_number', 'LIKE', "%$search%");
        }

        $status = $request->get('status');
        if (!is_null($status)){
            $orders->where('status', $status);
        }
        //end filter

        $orders = $orders->orderBy('id', 'desc')
            ->paginate($this->perPagePanel, ['*'], 'order_page');

        $transactions = $user->transactions();

        $type = $request->get('type');
        if(!is_null($type)){
            $transactions = $transactions->where('type', $type);
        }

        $start_date = !is_null($request->get('alt_start_date')) ? timestamp_to_date($request->get('alt_start_date')) : null ;
        $end_date = !is_null($request->get('alt_end_date')) ? timestamp_to_date($request->get('alt_end_date')) : null ;

        if (!is_null($start_date) and !is_null($end_date)){
            $transactions = $transactions->whereBetween('created_at', [$start_date, $end_date]);
        }

        $transactions = $transactions->orderBy('created_at', 'desc')
            ->paginate($this->perPagePanel, ['*'], 'transaction_page');


        //--------------------------------------------------------

        return view('panel.users.edit',compact('user', 'addresses', 'orders', 'transactions'));
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
            'name' => ['nullable', 'string', 'max:255'],
//            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,'.$id],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:users,phone,'.$id],
            'landline_phone' => ['nullable','numeric'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable'],
            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
            'city_id' => ['nullable', Rule::in(City::pluck('id'))],
        ]);
        $user = User::find($id);

        $input = $request->all();
        $input['date_of_birth'] = !is_null($request->get('date_of_birth')) ? timestamp_to_date($request->get('date_of_birth')) : $user->date_of_birth;

        $user->update($input);

        return redirect()->route('panel.users.index')
            ->with('success','کاربر با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('panel.users.index')
            ->with('success','کاربر با موفقیت حذف شد.');
    }

    public function search_in_users_list_json(Request $request){
        $query = User::select('id', 'name', 'phone', 'email')
                        ->with('addresses');

        $keyword = $request->get('q');
        if (!empty($keyword)) {
            $query->where(function($query) use($keyword){
                $query->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('phone', 'LIKE', "%$keyword%")
                        ->orWhere('email', 'LIKE', "%$keyword%");
            });
        }

        $users = $query->paginate($this->perPagePanel);
        return response($users);
    }

    public function change_user_status($user_id){
        $user = User::find($user_id);
        if ($user){
            $status = $user->status;

            if ($status == "active"){
                $user->status = "inactive";
                $user->save();
            }else{
                $user->status = "active";
                $user->save();
            }

            return redirect()->back()
                ->with('success', "وضعیت کاربر تغییر بافت.");
        }

        return redirect()->back()
            ->with('error', "کاربر یافت نشد.");
    }
}
