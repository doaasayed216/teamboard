<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsInvitationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_owner_can_invite_another_user()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $user = User::factory()->create();
        $this->actingAs($project->owner)
            ->post('/projects/'.$project->id.'/invitations', ['email' => $user->email]);

        $this->assertTrue($project->members->contains($user));
    }

    public function test_invited_members_cannot_invite_other_users()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $user = User::factory()->create();
        $this->actingAs($project->owner)
            ->post('/projects/'.$project->id.'/invitations', ['email' => $user->email]);

        $another_user = User::factory()->create();
        $this->actingAs($project->members->last())
            ->post('/projects/'.$project->id.'/invitations', ['email' => $another_user->email])
            ->assertStatus(403);

        $this->assertFalse($project->members->contains($another_user));
    }

    public function test_invited_users_must_have_doit_account()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->actingAs($project->owner)
            ->post('/projects/'.$project->id.'/invitations', ['email' => 'doaasayed@doit.com'])
            ->assertSessionHasErrors('email');
    }
}
