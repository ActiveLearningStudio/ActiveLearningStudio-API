<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Project;
use App\Http\Controllers\Api\V1\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test Function to create project*/
    public function createProject()
    {
        $user = $this->actingAs(factory('App\User')->create(), 'api');
        $project = factory('App\Models\Project')->make();
        $response = $this->withSession(['banned' => false])->postJson('/api/v1/suborganization/' . $project->organization_id . '/projects',$project->toArray());
        
        $response->assertStatus(201);
        
    }

    /** @test function for mandatory title*/
    public function ProjectRequiresATitle(){

        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['name' => null]);

        $this->withSession(['banned' => false])->post('/api/v1/suborganization/' . $project->organization_id . '/projects',$project->toArray())
                ->assertSessionHasErrors('name');
    }

    /** @test  function for mandatory description*/
    public function ProjectRequiresADescription(){

        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['description' => null]);

        $this->withSession(['banned' => false])->post('/api/v1/suborganization/' . $project->organization_id . '/projects',$project->toArray())
            ->assertSessionHasErrors('description');
    }

    /** @test  function for mandatory thumb url*/
    public function ProjectRequiresAThumbUrl(){

        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['thumb_url' => null]);

        $this->withSession(['banned' => false])->post('/api/v1/suborganization/' . $project->organization_id . '/projects',$project->toArray())
            ->assertSessionHasErrors('thumb_url');
    }

    /** @test function for mandatory organization visibilty id*/
    public function ProjectRequiresOrganizationVisibilityTypeId(){

        $this->actingAs(factory('App\User')->create(),'api');

        $project = factory('App\Models\Project')->make(['organization_visibility_type_id' => null]);

        $this->withSession(['banned' => false])->post('/api/v1/suborganization/' . $project->organization_id . '/projects',$project->toArray())
            ->assertSessionHasErrors('organization_visibility_type_id');
    }

    /** @test function for unauthenticated user create project*/
    public function unauthenticatedUsersCannotCreateNewProject()
    {
        //Given we have a task object
        $project = factory('App\Models\Project')->make();
        //When unauthenticated user submits post request to create task endpoint
        // He should be redirected to login page
        $this->postJson('/api/v1/suborganization/' . $project->organization_id . '/projects',$project->toArray())
        ->assertStatus(401);
    }


}
