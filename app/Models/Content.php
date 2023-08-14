<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static pluck(string $string)
 * @method static find($content_id)
 */
class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'cover_id'
    ];

    public function cover(){
        return $this->belongsTo(File::class, 'cover_id');
    }

    public function clusters(){
        return $this->hasMany(Cluster::class, 'content_id');
    }
}
