<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


/**
 * @property mixed id
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed phone
 * @property mixed email
 * @property mixed birth_date
 * @property mixed gender
 * @property mixed address
 * @property mixed landline_phone
 * @property mixed father_name
 * @property mixed mother_name
 * @property mixed first_visit
 * @property mixed diagnosis
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'address' => $this->address,
            'landline_phone' => $this->landline_phone,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'first_visit' => $this->first_visit,
            'diagnosis' => $this->diagnosis,
        ];
    }
}
