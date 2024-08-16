<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    $word = $this->faker->word();
    return [
      // 'user_id' => User::factory()->recycle(User::inRandomOrder()->first()), // Recycle an existing user or create a new one
      'title' => $word,
      // 'slug' => $this->faker->unique()->slug(),
      'slug' => $word,
      'quantity' => $this->faker->numberBetween(1, 25),
      'description' => $this->faker->paragraph(),
      'published' => $this->faker->boolean(),
      'in_stock' => $this->faker->boolean(),
      'price' => $this->faker->randomFloat(2, 10, 1000),
      'created_by' => "1", // Ensure an admin creates the product
      // 'updated_by' => User::factory()->admin(), // Optional: You can also set this to the same user as created_by
      'updated_by' => "1", // Optional: You can also set this to the same user as created_by
      // 'brand_id' => Brand::factory()->create()->id,  // Assuming you have a BrandFactory & that create a new one
      'brand_id' => Brand::inRandomOrder()->first()->id, // Recycle an existing brand
      'category_id' => Category::inRandomOrder()->first()->id, // Recycle an
    ];
  }
}
