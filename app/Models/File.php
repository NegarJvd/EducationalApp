<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static pluck(string $string)
 * @method static create(array $array)
 * @method static find($upload_id)
 */
class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'files';
    protected $primaryKey = 'id';

    protected $fillable = [
        'file_path'
    ];

//    public function admin(){
//        return $this->hasOne(Admin::class, "avatar_id");
//    }
//
//    public function user(){
//        return $this->hasOne(User::class, "avatar_id");
//    }

    public function content(){
        return $this->hasOne(Content::class, "cover_id");
    }

    public function cluster(){
        return $this->hasOne(Cluster::class, "cover_id");
    }

    public function step_cover(){
        return $this->hasOne(Step::class, "cover_id");
    }

    public function step_video(){
        return $this->hasOne(Step::class, "video_id");
    }
}
