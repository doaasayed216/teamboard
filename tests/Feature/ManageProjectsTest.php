<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_manage_projects()
    {
        $user = User::factory()->create();
        $project = Project::withoutEvents(function () use ($user){
            return Project::create([
                'owner_id' => $user->id,
                'title' => 'Project title',
                'description' => 'project description'
            ]);
        });
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/'.$project->id)->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get('/projects/'.$project->id.'/edit')->assertRedirect('login');
        $this->post('/projects')->assertRedirect('login');
        $this->patch('/projects/'.$project->id)->assertRedirect('login');
        $this->delete('/projects/'.$project->id)->assertRedirect('login');
    }

    public function test_project_required_a_title()
    {
        $this->signIn();
        $this->post('/projects', Project::factory()->raw(['title' => '']))
            ->assertSessionHasErrors('title');
    }

    public function test_project_required_a_description()
    {
        $this->signIn();
        $this->post('/projects', Project::factory()->raw(['description' => '']))
            ->assertSessionHasErrors('description');
    }

    public function test_user_can_create_projects()
    {
        $this->signIn();

        $this->get('/projects/create')->assertOk();

        $this->post('/projects', $attributes = Project::factory()->raw());

        $this->assertDatabaseHas('projects', $attributes);
    }

    public function test_users_can_view_their_projects()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->get('/projects/'.$project->id)->assertStatus(200);
    }

    public function test_users_can_edit_their_projects()
    {
        $project = Project::factory()->create(['owner_id' => $this->signIn()]);
        $this->get('/projects/'.$project->id.'/edit')->assertOk();
        $this->actingAs($project->owner)
            ->patch('/projects/' .$project->id, $attributes = [
                'title' => 'changed',
                'description' => $project->description
            ]);

        $this->assertDatabaseHas('projects', $attributes);
    }

    public function test_users_can_delete_their_projects()
    {
        $project = Project::factory()->create(['owner_id' => $this->signIn()]);
        $this->actingAs($project->owner)
            ->delete('/projects/' .$project->id);
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function test_unauthorized_users_cannot_manage_other_users_projects()
    {
        $this->signIn();
        $another_user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $another_user->id]);
        $this->get('/projects')->assertDontSee($project->title);
        $this->get('/projects/'.$project->id)->assertStatus(403);
        $this->get('/projects/'.$project->id.'/edit')->assertStatus(403);
        $this->patch('/projects/'.$project->id)->assertStatus(403);
        $this->delete('/projects/'.$project->id)->assertStatus(403);
    }
}
