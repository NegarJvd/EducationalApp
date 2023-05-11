<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $permissions = [
            'ticket-list',
            'ticket-reply',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }

        $this->middleware('permission:ticket-list', ['only' => ['index']]);
        $this->middleware('permission:ticket-reply', ['only' => ['edit','update']]);
    }

    public function index(Request $request){
        $tickets = Ticket::whereNull('parent_id');

        $keyword = $request->get('search');
        if (!empty($keyword)) {
            $tickets->where(function ($query) use ($keyword) {
                $query->where('subject', 'LIKE', "%$keyword%")
                    ->orWhere('text', 'LIKE', "%$keyword%");
            });
        }

        $tickets = $tickets->orderBy('id', 'desc')
            ->paginate($this->perPagePanel);

        return view('panel.tickets.index', compact('tickets'));
    }

    public function edit(Ticket $ticket){
        if (!is_null($ticket->parent_id)){
            $ticket = $ticket->parent()->first();
        }
        return view('panel.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $parent_id){
        $request->validate([
            'text' => 'required|string|max:255'
        ]);

        $admin = Auth::user();

        Ticket::create([
            'admin_id' => $admin->id,
            'subject' => Ticket::find($parent_id)->subject,
            'text' => $request->get('text'),
            'parent_id' => $parent_id
        ]);

        return redirect()->back()->with('success', "ارسال شد.");
    }
}
