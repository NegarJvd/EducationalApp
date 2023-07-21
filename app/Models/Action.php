<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'step_id', 'count', 'result'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function step(){
        return $this->belongsTo(Step::class, 'step_id');
    }

    public function cluster(){
        return $this->step()->cluster;
    }

    public function content(){
        return $this->cluster()->content;
    }
}
