<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    return [
      'type' => fake()->randomElement(['home', 'officie']),
      'address1' => $this->faker->streetAddress,
      'address2' => $this->faker->secondaryAddress,
      'city' => $this->faker->city,
      'state' => $this->faker->stateAbbr,
      'zipcode' => $this->faker->postcode,
      'country_code' => $this->faker->countryCode,
      'is_main' => 0,
      'user_id' => "3",
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
