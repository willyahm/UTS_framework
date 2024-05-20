<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name'        => $this->faker->firstNameMale(),
            'description' => $this->faker->paragraph(),
            'price'       => $this->faker->randomNumber(4, true),
            'image'       => $this->faker->word(),
            'expired_at'  => $this->faker->date()
        ];
    }
}
