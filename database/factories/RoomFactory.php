<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'room_number' => $this->faker->unique()->numberBetween(100, 999),
            'room_type' => $this->faker->randomElement(['single', 'double', 'suite']),
            'price_per_night' => $this->faker->numberBetween(50, 500),
            'status' => $this->faker->randomElement(['available', 'unavailable']),
        ];
    }
}
