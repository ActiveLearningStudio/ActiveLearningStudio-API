<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Models\Playlist;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlaylistTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test function to create playlist*/
    public function createPlaylist()
    {
        $user = $this->actingAs(User::factory()->create(), 'api');
        $playlist = Playlist::factory()->make();
        
        $response = $this->withSession(['banned' => false])
                        ->postJson(
                            '/v1/projects/' . $playlist->project_id . '/playlists',
                            $playlist->toArray()
                        );
        
        $response->assertStatus(201);
    }

    /** @test function to mandatory title for playlist*/
    public function playlistRequiresTitle()
    {
        $this->actingAs(User::factory()->create(),'api');

        $playlist = Playlist::factory()->make(['title' => null]);
        
        $this->withSession(['banned' => false])
            ->post(
                '/v1/projects/' . $playlist->project_id . '/playlists',
                $playlist->toArray()
            )
            ->assertSessionHasErrors('title');
    }

    /** @test function for unauthenticated user to create playlist*/
    public function unauthenticatedUsersCannotCreateANewPlaylist()
    {
        // Given we have a playlist object
        $playlist = Playlist::factory()->make();
        // When unauthenticated user submits post request to create playlist endpoint
        $this->postJson(
                '/v1/projects/' . $playlist->project_id . '/playlists',
                $playlist->toArray()
            )
            ->assertStatus(401);
    }

    /** @test function for authorized user to create playlist */
    public function authorizedUserCanUpdateThePlaylist() 
    {
        // Given we have a signed in user
        $this->actingAs(User::factory()->create(),'api');

        // And a playlist which is created by the user
        $playlist = Playlist::factory()->create();
        $playlist->title = "Updated Title";
        
        // When the user hit's the endpoint to update the playlist
        $response = $this->withSession(['banned' => false])
                        ->put(
                            '/v1/projects/'.$playlist->project_id.'/playlists/'. $playlist->id,
                             $playlist->toArray()
                        );
        
        $response->assertStatus(200);
    }
}
