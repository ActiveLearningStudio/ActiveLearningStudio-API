<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test Function to create project*/
    public function createProject()
    {
        // This step will mock the user session using user factory
        $user = $this->actingAs(factory('App\User')->create(), 'api');

        // It will create the object of project
        $project = factory('App\Models\Project')->make();

        // Post request to the create project route should return 201 status
        $response = $this->withSession(['banned' => false])
                            ->postJson(
                                '/api/v1/suborganization/' . $project->organization_id . '/projects',
                                $project->toArray()
                            );
        
        // API should return the 201 status code in order to success the test
        $response->assertStatus(201);
        
    }

    /** @test function for mandatory title*/
    public function projectRequiresTitle()
    {
        $this->actingAs(factory('App\User')->create(),'api');

        // Make project object with title as null
        $project = factory('App\Models\Project')->make(['name' => null]);

        // API call should return the name validation error message
        $this->withSession(['banned' => false])
            ->post(
                '/api/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('name');
    }

    /** @test  function for mandatory description*/
    public function projectRequiresDescription()
    {
        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['description' => null]);

        $this->withSession(['banned' => false])
            ->post(
                '/api/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('description');
    }

    /** @test  function for mandatory thumb url*/
    public function projectRequiresThumbUrl()
    {
        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['thumb_url' => null]);

        $this->withSession(['banned' => false])
            ->post(
                '/api/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('thumb_url');
    }

    /** @test function for mandatory organization visibilty id*/
    public function projectRequiresOrganizationVisibilityTypeId()
    {
        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['organization_visibility_type_id' => null]);

        $this->withSession(['banned' => false])
            ->post(
                '/api/v1/suborganization/' . $project->organization_id . '/projects',
                $project->toArray()
            )
            ->assertSessionHasErrors('organization_visibility_type_id');
    }

    /** @test function for unauthenticated user create project*/
    public function unauthenticatedUsersCannotCreateNewProject()
    {
        // Given we have a project object
        $project = factory('App\Models\Project')->make();
        // When unauthenticated user submits post request to create project endpoint
        // He should be retrun the 401 authorization status code
        $this->postJson(
                '/api/v1/suborganization/' . $project->organization_id . '/projects', 
                $project->toArray()
            )
            ->assertStatus(401);
    }
}
