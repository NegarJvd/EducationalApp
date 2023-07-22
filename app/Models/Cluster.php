<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find($cluster_id)
 */
class Cluster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clusters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content_id', 'name', 'description', 'cover_id'
    ];

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
