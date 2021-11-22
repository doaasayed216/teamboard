<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    public function allProjects()
    {
       return $this->projects()->orWhereHas('members', function ($query){
           $query->where('user_id', $this->id);
       });
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function recordActivity(Project $project, $type)
    {
        $this->activity()->create([
            'project_id' => $project->id,
            'user_id' => auth()->user()->id,
            'type' => $type
        ]);
    }
}
