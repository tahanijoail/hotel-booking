<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Hotel',
            'contacts' => [
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->safeEmail,
                'address' => $this->faker->address,
            ],
        ];
    }
}
