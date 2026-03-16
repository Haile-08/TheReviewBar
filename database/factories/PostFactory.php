<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'movie' => $this->faker->sentence(3),
            'poster' => $this->faker->imageUrl(640, 480, 'movies'),
            'upvote' => $this->faker->numberBetween(0, 500),
            'downvote' => $this->faker->numberBetween(0, 100),
            'description' => $this->faker->paragraphs(3, true),
        ];
    }
}
