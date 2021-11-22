<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incompleted_task');
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function recordActivity($type)
    {
        $this->activity()->create([
            'project_id' => $this->project->id,
            'user_id' => auth()->user()->id,
            'type' => $type
        ]);
    }

    public function assigned_to()
    {
        return $this->belongsToMany(User::class, 'task_responsible');
    }

    public function assign(User $user)
    {
        $this->assigned_to()->attach($user);
        $this->recordActivity('assigned_user');
    }

    public function unassign($user)
    {
        $this->assigned_to()->detach($user);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
