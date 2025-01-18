<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSetting extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'map_iframe',
        'location',
        'email',
        'phone',
        'timing',
        'created_at',
        'updated_at',
        'deleted_at',
        'updated_by',
        'created_by',
    ];

    public function get_contact_us_details()
    {
        return ContactSetting::where('static_id', 1)->where('status', 1)->first();
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
