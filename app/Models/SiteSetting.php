<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status',
        'key',
        'value',
        'title',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'updated_by',
        'created_by',
    ];

    public function getSiteSettings($key = null)
    {
        if ($key) {
            return SiteSetting::where('key', $key)->where('status', 1)->first();
        }
        return null;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
