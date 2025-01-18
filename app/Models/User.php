<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'is_verified',
        'phone',
        'username',
        'address',
        'dateofbirth',
        'profileimage',
        'otp',
        'updated_by',
        'created_by',
        'organization_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    static public function get_user_by_id($id)
    {
        $User = [];
        $user = User::find($id);
        if ($user) {
            $User = $user;
        } else {
            $User['name'] = 'User Deleted';
        }
        return $User;
    }
    static public function get_total_use_referral_user_by_id($id)
    {
        $user = User::find($id);
        if ($user) {
            $user_referral_code = $user->referral_code;
            $user_other_referral_code = User::where('other_referral_code', $user_referral_code)->count();
            return $user_other_referral_code;
        } else {
            return 0;
        }
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_id');
    }

    /**
     * Get the tasks owned by the user.
     */
    public function ownedTasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function updatedTasks()
    {
        return $this->hasMany(Task::class, 'updated_by');
    }

    public function clientProjects()
    {
        return $this->hasMany(Task::class,'client_id');
    }
}
