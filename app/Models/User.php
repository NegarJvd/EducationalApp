<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static where(string $string, $get)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $guard = 'api';

    protected $table = 'users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'birth_date',
        'address',
        'gender',
        'landline_phone',
        'father_name',
        'mother_name',
        'first_visit',
        'diagnosis'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'name', 'parent_name'
    ];

    function getNameAttribute() {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    function getParentNameAttribute() {
        return $this->attributes['father_name'] . ' - ' . $this->attributes['mother_name'];
    }

    public static function gender(){
        return ['male', 'female'];
    }

    public function contents(){
        return $this->belongsToMany(Content::class, 'content_user', 'user_id', 'content_id')
            ->withPivot('cluster_id');
    }

    public function clusters(){
        return $this->belongsToMany(Cluster::class, 'content_user', 'user_id', 'cluster_id')
            ->withPivot('content_id');
    }

}
