<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find($cluster_id)
 * @method static pluck(string $string)
 * @method static where(string $string, $content_id)
 * @method static whereIn(string $string, array $clusters_id_list)
 */
class Cluster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clusters';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        // deleting files
        self::deleted(function ($model) {
            $cluster_cover = $model->cover_id;
            $file = File::withTrashed()->find($cluster_cover);
            if (file_exists($file->file_path)){
                unlink($file->file_path);
            }
        });
    }

    protected $fillable = [
        'content_id', 'name', 'description', 'cover_id'
    ];

    protected $appends = [
        'cover_image',
        'best_parent_score', 'best_therapist_score'
    ];

    function getCoverImageAttribute() {
        if(!is_null($this->attributes['cover_id']) and file_exists($this->cover->file_path))
            return asset($this->cover->file_path);
        return asset('/assets/img/cc1b.jpg');
    }

    function getBestParentScoreAttribute() {
        return $this->steps()->count() * 2;
    }

    function getBestTherapistScoreAttribute() {
        return $this->steps()->count() * 7;
    }

    public function cover(){
        return $this->belongsTo(File::class, 'cover_id');
    }

    public function content(){
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function steps(){
        return $this->hasMany(Step::class, 'cluster_id');
    }
}
