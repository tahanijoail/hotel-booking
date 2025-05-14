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
            'location' => $this->faker->city, // ✅ أضف هذا السطر
             'description' => $this->faker->paragraph, // ✅ أضف هذا السطر
             'number_of_rooms' => $this->faker->numberBetween(10, 100), // ✅ أضف هذا السطر
            'contacts' => [
                'phone' => $this->faker->phoneNumber,
                'email' => $this->faker->safeEmail,
                'address' => $this->faker->address,
            ],
        ];
    }
}
