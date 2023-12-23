<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static pluck(string $string)
 * @method static whereIn(string $string, $steps_id_list)
 * @method static find($get)
 * @method static where(string $string, $cluster_id)
 */
class Step extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'steps';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        // deleting files
        static::deleting(function ($model) {
            $step_cover = $model->cover_id;
            $cover_file = File::withTrashed()->find($step_cover);
            if (file_exists($cover_file->file_path)){
                unlink($cover_file->file_path);
            }
            $cover_file->delete();

            $step_video = $model->video_id;
            $video_file = File::withTrashed()->find($step_video);
            if (file_exists($video_file->file_path)){
                unlink($video_file->file_path);
            }
            $video_file->delete();
        });
    }

    protected $fillable = [
        'cluster_id', 'number', 'description', 'cover_id', 'video_id'
    ];

    protected $appends = [
        'cover_image', 'video_file'
    ];

    function getCoverImageAttribute() {
        if(array_key_exists('cover_id', $this->attributes) and !is_null($this->attributes['cover_id'])
            and $this->cover and file_exists($this->cover->file_path))
            return asset($this->cover->file_path);
        return null;
    }

    function getVideoFileAttribute() {
        if(array_key_exists('video_id', $this->attributes) and !is_null($this->attributes['video_id'])
            and $this->video and file_exists($this->video->file_path))
            return asset($this->video->file_path);
        return null;
    }

    public function cover(){
        return $this->belongsTo(File::class, 'cover_id');
    }

    public function video(){
        return $this->belongsTo(File::class, 'video_id');
    }

    public function cluster(){
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function actions(){
        return $this->hasMany(Action::class, 'step_id');
    }

}
