<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $path = $model->file_path;

            if (file_exists($path)){
                unlink($path);
            }

//            if ($upload->user){
//                $user = $upload->user()->first();
//                $user->avatar_id = null;
//                $user->save();
//            }

            if ($model->content){
                $content = $model->content()->first();
                $content->cover_id = null;
                $content->save();
            }

            if ($model->cluster){
                $cluster = $model->cluster()->first();
                $cluster->cover_id = null;
                $cluster->save();
            }

            if ($model->step_cover){
                $step_cover = $model->step_cover()->first();
                $step_cover->cover_id = null;
                $step_cover->save();
            }

            if ($model->step_video){
                $step_video = $model->step_video()->first();
                $step_video->video_id = null;
                $step_video->save();
            }
        });
    }

    protected $fillable = [
        'file_path'
    ];

    protected $appends = [
        'file_name', 'size'
    ];

    function getFileNameAttribute() {
        $array = explode("/",$this->attributes['file_path']);
        return end($array);
    }

    function getSizeAttribute() {
        return Storage::size($this->attributes['file_path']);
    }

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
