<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTest extends TestCase
{
    use DatabaseTransactions, HasFactory;
    
    /** @test Function to create project*/
    public function createProject()
    {
        // This step will mock the user session using user factory
        $user = $this->actingAs(User::factory()->create(), 'api');
        

        // It will create the object of project
        $project = Project::factory()->make();

        // Post request to the create project route should return 201 status
        $response = $this->withSession(['banned' => false])
                            ->postJson(
                                '/v1/suborganization/' . $project->organization_id . '/projects',
                                $project->toArray()
                            );
        
        // API should return the 201 status code in order to success the test
        $response->assertStatus(201);
        
    }

    /** @test function for mandatory title*/
    public function projectRequiresTitle()
    {
        $this->actingAs(User::factory()->create(),'api');

        // Make project object with title as null
        $project = Project::factory()->make(['name' => null]);

        // API call should return the name validation error message
        $this->withSession(['banned' => false])
            ->post(
                '/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('name');
    }

    /** @test  function for mandatory description*/
    public function projectRequiresDescription()
    {
        $this->actingAs(User::factory()->create(),'api');

        $project = Project::factory()->make(['description' => null]);

        $this->withSession(['banned' => false])
            ->post(
                '/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('description');
    }

    /** @test  function for mandatory thumb url*/
    public function projectRequiresThumbUrl()
    {
        $this->actingAs(User::factory()->create(),'api');

        $project = Project::factory()->make(['thumb_url' => null]);

        $this->withSession(['banned' => false])
            ->post(
                '/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('thumb_url');
    }

    /** @test function for mandatory organization visibilty id*/
    public function projectRequiresOrganizationVisibilityTypeId()
    {
        $this->actingAs(User::factory()->create(),'api');

        $project = Project::factory()->make(['organization_visibility_type_id' => null]);

        $this->withSession(['banned' => false])
            ->post(
                '/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('organization_visibility_type_id');
    }

    /** @test function for unauthenticated user create project*/
    public function unauthenticatedUsersCannotCreateNewProject()
    {
        // Given we have a project object
        $project = Project::factory()->make();
        // When unauthenticated user submits post request to create project endpoint
        // He should be retrun the 401 authorization status code
        $this->postJson(
                '/v1/suborganization/' . $project->organization_id . '/projects', 
                $project->toArray()
            )
            ->assertStatus(401);
    }
}
