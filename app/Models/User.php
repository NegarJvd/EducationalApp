<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static where(string $string, $get)
 * @method static find($id)
 * @method static count()
 * @method static whereBetween(string $string, array $array)
 * @method static pluck(string $string)
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
        'diagnosis',
        'admin_id',
        'avatar_id'
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

    public function avatar_path(){
        $upload = File::find($this->avatar_id);
        return asset($upload->file_path);
    }

    function getNameAttribute() {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    function getParentNameAttribute() {
        return $this->attributes['father_name'] . ' - ' . $this->attributes['mother_name'];
    }

    public static function gender(){
        return ['male', 'female'];
    }

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function contents(){
        return $this->belongsToMany(Content::class, 'content_user', 'user_id', 'content_id')
            ->withPivot('cluster_id');
    }

    public function clusters(){
        return $this->belongsToMany(Cluster::class, 'content_user', 'user_id', 'cluster_id')
            ->withPivot('content_id');
    }

    public function actions(){
        return $this->hasMany(Action::class, 'user_id');
    }

    public function tickets(){
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function latest_ticket()
    {
        return $this->hasOne(Ticket::class, 'user_id')->latest('created_at');
    }

    public function otp(){
        return $this->hasMany(OTP::class);
    }
}
