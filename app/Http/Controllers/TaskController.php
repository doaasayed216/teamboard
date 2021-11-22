<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('manage', $project);
        $attributes = $request->validate(['title' => 'required']);
        $attributes['project_id'] = $project->id;
        Task::create($attributes);
        return back();
    }

    public function show(Project $project, Task $task)
    {
        return view('tasks.show', ['task' => $task]);
    }

    public function update(Project $project, Task $task)
    {
        $attributes = request()->validate(['title' => 'required']);

        $task->update($attributes);

        if(URL::previous() == 'http://127.0.0.1:8000/projects/'.$project->id) {
            request('completed') ? $task->complete() : $task->incomplete();
            return back();
        }

        if(request('description')) {
            $task->update(['description' => request()->input('description')]);
        }

        if(request('due_to')) {
            $task->update(['due_to' => request()->input('due_to')]);
        }

        if(request('member_id')) {
            foreach (request('member_id') as $id) {
                $user = User::where('id', $id)->first();
                if(!$task->assigned_to->contains($user)) {
                    $task->assign($user);
                }
            }
        }

        foreach($task->assigned_to as $user) {
            if(!request('member_id') or !in_array($user->id, request('member_id'))){
                $task->unassign($user);
            }
        }

        return back();
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();
        $task->activity()->delete();
        return redirect('/projects/'.$project->id);
    }
}
