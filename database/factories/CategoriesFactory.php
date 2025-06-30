<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categories>
 */
class CategoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'Name' => $this->faker->word(),
        'Is_active' => $this->faker->boolean(),
        'Parent_id' => $this->faker->optional()->numberBetween(1, 10), // hoặc null
        'Image' => $this->faker->imageUrl(640, 480, 'category', true),
        ];
    }
}
