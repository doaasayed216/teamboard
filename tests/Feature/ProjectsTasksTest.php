<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Auth\PasswordResetTest;
use Tests\TestCase;

class ProjectsTasksTest extends TestCase
{
    use RefreshDatabase;
    public function test_project_can_have_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->post('/projects/'.$project->id.'/tasks', $task =Task::factory()->raw());
        $this->assertDatabaseHas('tasks', ['title' => $task['title']]);
    }

    public function test_task_required_a_title()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->post('/projects/'.$project->id.'/tasks', Task::factory()->raw(['title' => '']))
            ->assertSessionHasErrors('title');

    }

    public function test_only_owner_can_add_tasks()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => User::factory()->create()->id]);
        $this->post('/projects/'.$project->id.'/tasks', Task::factory()->raw())
            ->assertStatus(403);
    }

    public function test_task_can_be_updated()
    {
        //$this->withExceptionHandling();
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $this->patch('/projects/'.$project->id.'/tasks/'.$task->id,
            $attributes = ['title' => 'changed']);
        $this->assertDatabaseHas('tasks', $attributes);
    }


    public function test_owner_can_assign_task_to_member()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $user = User::factory()->create();
        $this->post('/projects/'.$project->id.'/invitations', ['email' => $user->email]);
        $task = Task::factory()->create(['project_id' => $project->id]);
        $task->assign($user);
        $this->assertTrue($task->assigned_to->contains($user));
    }

    public function test_me()
    {
        
    }
}
