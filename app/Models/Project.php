<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\UnexpectedSessionUsageException;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $old = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function invite(User $user)
    {
        $this->members()->attach($user);
        $user->recordActivity($this, 'added_member');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->orderByDesc('created_at');
    }

    public function recordActivity($type)
    {
        $this->activity()->create([
            'project_id' => $this->id,
            'user_id' => auth()->user()->id,
            'type' => $type,
            'changes' => [
                'before' => array_diff($this->old, $this->getAttributes()),
                'after' => array_diff($this->getAttributes(), $this->old)
            ]
        ]);
    }
}
