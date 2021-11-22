<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);
        $attributes = $request->validate([
            'user_id' => 'required|exists:users,id',
            'body' => 'required'
        ]);

        $task->comments()->create($attributes);
        return back();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back();
    }
}
