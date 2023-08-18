<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static pluck(string $string)
 * @method static whereIn(string $string, $steps_id_list)
 */
class Step extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'steps';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        // deleting files of steps
        self::deleted(function ($model) {
            //delete files
        });
    }

    protected $fillable = [
        'cluster_id', 'number', 'description', 'cover_id', 'video_id'
    ];

    protected $appends = [
        'cover_image', 'video_file'
    ];

    function getCoverImageAttribute() {
        return asset('/assets/img/cc1b.jpg');
    }

    function getVideoFileAttribute() {
        return asset('/assets/img/cd_5.mp4');
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
