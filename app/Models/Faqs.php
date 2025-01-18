<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faqs extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'status',
        'title',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'updated_by',
        'created_by',
    ];

    static public function get_all_faqs()
    {
        return Faqs::where('status', 1)->get();
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
