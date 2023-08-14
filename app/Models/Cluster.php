<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find($cluster_id)
 * @method static pluck(string $string)
 * @method static where(string $string, $content_id)
 */
class Cluster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clusters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'content_id', 'name', 'description', 'cover_id'
    ];

    protected $appends = [
        'best_parent_score', 'best_therapist_score'
    ];

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
