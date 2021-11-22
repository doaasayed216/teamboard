<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use function Ramsey\Uuid\v1;

class ProjectController extends Controller
{
    public function index()
    {
        return view('dashboard', ['projects' => auth()->user()->allProjects]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        $attributes = array_merge($this->validateProject(), [
            'owner_id' => auth()->user()->id
        ]);

        Project::create($attributes);

        return redirect('/projects');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('projects.show', ['project' => $project, 'activities' => $project->activity()->simplePaginate(10)]);
    }

    public function edit(Project $project)
    {
        $this->authorize('manage', $project);
        return view('projects.edit', ['project' => $project]);
    }

    public function update(Project $project)
    {
        $this->authorize('manage', $project);

        $project->update($this->validateProject());

        return back();
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();
        return back();
    }

    protected function validateProject()
    {
        return request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
    }
}
