<?php

namespace Database\Factories;

use App\Models\Playlist;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class PlaylistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'project_id' => Project::factory()->create()->id,
        ];
    }
}
