<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogTag extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'blog_tag';

    protected $fillable = [
        'blog_id',
        'tag_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
