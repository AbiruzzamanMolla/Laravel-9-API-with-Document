<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence;

        return [
            'uuid' => (string) Str::uuid()->toString(),
            'title' => (string) $title,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'image' => $this->faker->imageUrl(),
            'user_id' => User::all()->random()->id,
        ];
    }
}
