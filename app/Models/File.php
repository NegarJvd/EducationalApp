<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static pluck(string $string)
 */
class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $primaryKey = 'id';

    protected $fillable = [
        'file_path'
    ];
}
