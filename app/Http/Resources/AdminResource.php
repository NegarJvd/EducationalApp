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
 * @property mixed field_of_profession
 * @property mixed resume
 * @property mixed degree_of_education
 * @property mixed medical_system_number
 * @method users()
 */
class AdminResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'medical_system_number' => $this->medical_system_number ,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'address' => $this->address,
            'landline_phone' => $this->landline_phone,
            'field_of_profession' => $this->field_of_profession,
            'resume' => $this->resume,
            'degree_of_education' => $this->degree_of_education,
            'users_count' => $this->users()->count()
        ];
    }
}
