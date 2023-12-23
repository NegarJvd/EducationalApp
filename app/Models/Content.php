<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static pluck(string $string)
 * @method static find($content_id)
 * @method static create(array $only)
 */
class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contents';
    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        // deleting files
        static::deleting(function ($model) {
            $content_cover = $model->cover_id;
            $file = File::withTrashed()->find($content_cover);
            if($file){
                if (file_exists($file->file_path)){
                    unlink($file->file_path);

                }
                $file->delete();
            }
        });
    }

    protected $fillable = [
        'name', 'cover_id'
    ];

    protected $appends = [
        'cover_image',
    ];

    function getCoverImageAttribute() {
        if(array_key_exists('cover_id', $this->attributes) and !is_null($this->attributes['cover_id'])
            and $this->cover and file_exists($this->cover->file_path))
            return asset($this->cover->file_path);
        return null;
    }

    public function cover(){
        return $this->belongsTo(File::class, 'cover_id');
    }

    public function clusters(){
        return $this->hasMany(Cluster::class, 'content_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'content_user', 'content_id', 'user_id')
            ->withPivot('cluster_id');
    }
}
