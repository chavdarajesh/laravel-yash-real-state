<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status', 'email',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
