<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;

    protected $table = "otp";
    protected $primaryKey = "id";

    protected $fillable = [
        'user_id', 'otp', 'verification'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
