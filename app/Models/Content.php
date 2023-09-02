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

        // deleting files of steps
        self::deleted(function ($model) {
            //delete files
        });
    }

    protected $fillable = [
        'name', 'cover_id'
    ];

    protected $appends = [
        'cover_image',
    ];

    function getCoverImageAttribute() {
        if(!is_null($this->attributes['cover_id']) and file_exists($this->cover->file_path))
            return asset($this->cover->file_path);
        return asset('/assets/img/cc1b.jpg');
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
