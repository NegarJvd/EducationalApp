<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserWithTicketsResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('permission:ticket-list|ticket-reply');
        $this->middleware('permission:ticket-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:ticket-reply', ['only' => ['store']]);
    }

    public function index(Request $request)
    {
        $admin = Auth::user();

        $data = User::query()
                    ->where('admin_id', $admin->id)
                    ->whereHas('tickets')
                    ->withAggregate('tickets','created_at', 'max')
                    ->with('latest_ticket');

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

        $data = $data->orderBy('tickets_max_created_at','DESC')
                    ->paginate($this->perPagePanel);

        return view('panel.tickets.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id){
        $user = User::find($id);

        return $this->customSuccess(UserWithTicketsResource::make($user), "لیست تیکت ها");
    }

    public function store(Request $request){
        $admin = Auth::user();

        $request->validate([
            'user_id' => ['required', Rule::in($admin->users()->pluck('id'))],
            'text' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find($request->get('user_id'));

        if($user->admin_id != $admin->id)
            return $this->accessDenied();

        $ticket = Ticket::create([
            'text' => $request->get('text'),
            'user_id'=> $request->get('user_id'),
            'admin_id' => $admin->id
        ]);

        return $this->customSuccess(TicketResource::make($ticket), "تیکت با موفقیت ثبت شد.");
    }
}
