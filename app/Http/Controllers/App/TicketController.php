<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function get_list_of_tickets(){
        $user = Auth::user();

        $tickets = $user->tickets()->orderBy('created_at', 'asc')->get();

        return $this->customSuccess(TicketResource::collection($tickets), "لیست پیام ها");
    }

    public function store_ticket(Request $request){
        $request->validate([
            'text' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        $user->tickets()->create($request->only('text'));

        return $this->customSuccess(1, "تیکت با موفقیت ثبت شد.");
    }
}
