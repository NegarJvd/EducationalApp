<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\City;
//use App\Models\Option;
use App\Models\Order;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(User::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'first_visit' => ['nullable'],
            'diagnosis' => ['nullable', 'string', 'max:255'],

//            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
        ]);

        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date')) : null;

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

        if($request->wantsJson()){
            return $this->success(User::with('addresses')->find($user->id), "مراجعه کننده جدید با موفقیت اضافه شد.");
        }

        return redirect()->route('panel.users.index')
            ->with('success','مراجعه کننده با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        return view('panel.users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function edit(User $user)
    {
        return view('panel.users.edit',compact('user'));
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required','numeric','digits:11','regex:/^(09)/', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'birth_date' => ['nullable'],
            'gender' => ['nullable', Rule::in(User::gender())],
            'address' => ['nullable', 'string', 'max:255'],
            'landline_phone' => ['nullable', 'numeric', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'first_visit' => ['nullable'],
            'diagnosis' => ['nullable', 'string', 'max:255'],

//            'avatar_id' => ['nullable', Rule::in(Upload::pluck('id'))],
        ]);
        $user = User::find($id);

        $input = $request->all();
        $input['birth_date'] = !is_null($request->get('birth_date')) ? timestamp_to_date($request->get('birth_date')) : $user->birth_date;

        $user->update($input);

        return redirect()->route('panel.users.index')
            ->with('success','مراجعه کننده با موفقیت ویرایش شد.');
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
            ->with('success','مراجعه کننده با موفقیت حذف شد.');
    }

    public function search_in_users_list_json(Request $request){
        $query = User::select('id', 'first_name', 'last_name', 'phone', 'email', 'father_name', 'mother_name');

        $keyword = $request->get('q');
        if (!empty($keyword)) {
            $query->where(function($query) use($keyword){
                $query->where('first_name', 'LIKE', "%$keyword%")
                        ->orWhere('last_name', 'LIKE', "%$keyword%")
                        ->orWhere('phone', 'LIKE', "%$keyword%")
                        ->orWhere('email', 'LIKE', "%$keyword%")
                        ->orWhere('father_name', 'LIKE', "%$keyword%")
                        ->orWhere('mother_name', 'LIKE', "%$keyword%");
            });
        }

        $users = $query->paginate($this->perPagePanel);
        return response($users);
    }

//    public function change_user_status($user_id){
//        $user = User::find($user_id);
//        if ($user){
//            $status = $user->status;
//
//            if ($status == "active"){
//                $user->status = "inactive";
//                $user->save();
//            }else{
//                $user->status = "active";
//                $user->save();
//            }
//
//            return redirect()->back()
//                ->with('success', "وضعیت مراجعه کننده تغییر بافت.");
//        }
//
//        return redirect()->back()
//            ->with('error', "مراجعه کننده یافت نشد.");
//    }
}
