<?php

namespace Database\Factories;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    protected $model = ShortUrl::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->words(3, true),
            'original_url' => fake()->url(),
            'short_code' => fake()->unique()->lexify('link????'),
            'clicks' => 0,
        ];
    }
}
