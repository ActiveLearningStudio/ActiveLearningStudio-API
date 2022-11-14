<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Playlist;
use App\Http\Controllers\Api\V1\PlaylistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlaylistTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test function to create playlist*/
    public function createPlaylist()
    {
        $user = $this->actingAs(factory('App\User')->create(), 'api');
        $playlist = factory('App\Models\Playlist')->make();
        
        $response = $this->withSession(['banned' => false])->postJson('/api/v1/projects/' . $playlist->project_id . '/playlists',$playlist->toArray());
        
        $response->assertStatus(201);
        
    }

    /** @test function to mandatory title for playlist*/
    public function PlaylistRequiresATitle(){

        $this->actingAs(factory('App\User')->create(),'api');

        $playlist = factory('App\Models\Playlist')->make(['title' => null]);
        $project = factory('App\Models\Project')->create()->id;
        
        $this->withSession(['banned' => false])->post('/api/v1/projects/' . $playlist->project_id . '/playlists',$playlist->toArray())
                ->assertSessionHasErrors('title');
    }


    /** @test function for unauthenticated user to create playlist*/
    public function unauthenticatedUsersCannotCreateANewPlaylist()
    {
        //Given we have a task object
        $playlist = factory('App\Models\Playlist')->make();
        //When unauthenticated user submits post request to create task endpoint
        $this->postJson('/api/v1/projects/' . $playlist->project_id . '/playlists',$playlist->toArray())
        ->assertStatus(401);
    }

    /** @test function for authorized user to create playlist */
    public function authorizedUserCanUpdateThePlaylist() {
        
        //Given we have a signed in user
        $this->actingAs(factory('App\User')->create(),'api');

        //And a task which is created by the user
        $playlist = factory('App\Models\Playlist')->create();
        $playlist->title = "Updated Title";
        
        //When the user hit's the endpoint to update the task
        $response = $this->withSession(['banned' => false])->put('/api/v1/projects/'.$playlist->project_id.'/playlists/'. $playlist->id, $playlist->toArray());
        
        $response->assertStatus(200);
        

    }
}
