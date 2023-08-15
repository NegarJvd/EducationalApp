<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

/**
 * @property mixed admin_id
 * @property mixed admin
 * @property mixed user
 * @property mixed text
 * @property mixed created_at
 */
class TicketResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'is_user' => $this->admin_id ? 0 : 1,
            'author_name' => $this->admin_id ? $this->admin->name : $this->user->name,
            'text' => $this->text,
            'datetime' => $this->created_at->toDateTimeString(),
            'jalali_datetime' => Jalalian::forge($this->created_at)->format('%H:%M - %Y/%m/%d'),
        ];
    }
}
