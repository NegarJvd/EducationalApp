<?php

namespace Negar\Smsirlaravel\models;

use Illuminate\Database\Eloquent\Model;

class SmsirlaravelLogs extends Model
{
	protected $guarded = [];
	protected $table = 'smsirlaravel_logs';
	protected $fillable = [
            'from', 'to', 'message', 'status', 'response'
    ];
	public $timestamps = true;

	public function sendStatus() {
		if($this->status){
			return '<i class="fa fa-check-circle" aria-hidden="true" style="color: green"></i>';
		}

		return '<i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red"></i>';

	}
}
