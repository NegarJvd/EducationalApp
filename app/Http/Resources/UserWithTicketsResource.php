<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


/**
 * @property mixed id
 * @property mixed name
 * @property mixed phone
 * @property mixed email
 * @property mixed first_visit
 * @property mixed diagnosis
 * @property mixed tickets
 */
class UserWithTicketsResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'first_visit' => $this->first_visit,
            'diagnosis' => $this->diagnosis,
            'tickets' => TicketResource::collection($this->tickets()->orderBy('created_at', 'asc')->get()),
        ];
    }
}
