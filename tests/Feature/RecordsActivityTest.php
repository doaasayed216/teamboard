<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordsActivityTest extends TestCase
{
    use RefreshDatabase;
    public function test_creating_a_project()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity->last()->type);
    }

    public function test_updating_project()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->patch('/projects/'.$project->id, ['title' => 'title changed', 'description' => 'description changed']);
        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->type);
    }

    public function test_adding_a_member()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $user = User::factory()->create();
        $this->post('/projects/'.$project->id.'/invitations', ['email' => $user->email]);
        $this->assertCount(2, $project->activity);
        $this->assertEquals('added_member', $project->activity->last()->type);
        $this->assertInstanceOf(User::class, $project->activity->last()->subject);
    }

    public function test_adding_a_task()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $project->tasks()->create(['title' => 'new task', 'description' => 'new task']);
        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->type);
        $this->assertInstanceOf(Task::class, $project->activity->last()->subject);
    }

    public function test_completing_a_task()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $task = $project->tasks()->create(['title' => 'new task']);
        $task->complete();
        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->type);
        $this->assertInstanceOf(Task::class, $project->activity->last()->subject);
    }

    public function test_incompleting_a_task()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $task = $project->tasks()->create(['title' =>'new task']);
        $task->complete();
        $task->incomplete();
        $this->assertCount(4, $project->activity);
        $this->assertEquals('incompleted_task', $project->activity->last()->type);
        $this->assertInstanceOf(Task::class, $project->activity->last()->subject);
    }

    public function test_assigning_user_to_a_task()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $user = User::factory()->create();
        $task = $project->tasks()->create(['title' => 'new task']);
        $this->post('/projects/'.$project->id.'/invitations', ['email' => $user->email]);
        $task->assign($user);
        $this->assertCount(4, $project->activity);
        $this->assertEquals('assigned_user', $project->activity->last()->type);
    }
}
