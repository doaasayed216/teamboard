<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('manage', $project);
        $input = $request->validate(['email' => 'required|exists:users,email']);
        $user = User::whereEmail($input['email'])->first();
        $project->invite($user);
        return back();
    }
}
