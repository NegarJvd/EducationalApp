<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Step extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'steps';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cluster_id', 'number', 'description', 'cover_id', 'video_id'
    ];

    public function cover(){
        return $this->hasOne(File::class, 'cover_id');
    }

    public function video(){
        return $this->hasOne(File::class, 'video_id');
    }

    public function cluster(){
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

}
