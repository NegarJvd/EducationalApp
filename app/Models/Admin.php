<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method static where(string $string, $get)
 * @method static create(array $array)
 * @method static find($id)
 * @method static pluck(string $string)
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guard = 'web';

    protected $table = 'admins';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'first_name', 'last_name',
        'phone', 'email',
        'medical_system_number',
        'birth_date',
        'gender', 'address', 'landline_phone',
        'password',
        'field_of_profession', 'resume', 'degree_of_education'
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
        'name'
    ];

    function getNameAttribute() {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    public static function status(){
        return ['inactive', 'active'];
    }

    public static function gender(){
        return ['male', 'female'];
    }

    public static function degree_of_education(){
        return ["کارشناسی", "ارشد", "دکتری"];
    }

    public function users(){
        return $this->hasMany(User::class, 'admin_id');
    }

}
