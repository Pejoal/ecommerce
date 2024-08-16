<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder {
  /**
   * Run the database seeds.
   */

  public function run(): void {

    User::create([
      'firstname' => 'Admin',
      'lastname' => '1',
      'username' => 'admin',
      'gender' => 'male',
      'email' => 'admin@mail.com',
      'email_verified_at' => now(),
      'password' => bcrypt('88888888'),
      'type' => 'super admin',
    ]);
    User::create([
      'firstname' => 'Admin',
      'lastname' => '2',
      'username' => 'admin2',
      'gender' => 'female',
      'email' => 'admin2@mail.com',
      'email_verified_at' => now(),
      'password' => bcrypt('88888888'),
      'type' => 'super admin',
    ]);

    User::create([
      'firstname' => 'client',
      'lastname' => '1',
      'username' => 'client1',
      'gender' => 'male',
      'email' => 'client1@example.com',
      'email_verified_at' => now(),
      'password' => bcrypt('11111111'),
      'type' => 'client',
    ]);

    // Brand::factory()
    //   ->count(5)
    //   ->create();

    // Category::factory()
    //   ->count(5)
    //   ->create();

    Category::create([
      'name' => 'Computer',
      'slug' => 'computer',
    ]);
    Category::create([
      'name' => 'Phone',
      'slug' => 'phone',
    ]);
    Category::create([
      'name' => 'Watch',
      'slug' => 'watch',
    ]);

    Brand::create([
      'name' => 'Dell',
      'slug' => 'dell',
    ]);
    Brand::create([
      'name' => 'Samsung',
      'slug' => 'samsung',
    ]);
    Brand::create([
      'name' => 'Apple',
      'slug' => 'apple',
    ]);

    Product::create([
      'title' => 'Product 1',
      'price' => 19.99,
      'quantity' => 3,
      'category_id' => 1,
      'brand_id' => 1,
      'description' => 'Product 1 Description',
      'created_by' => 1,
      'updated_by' => 1,
    ]);
    Product::create([
      'title' => 'Product 2',
      'price' => 30,
      'quantity' => 5,
      'category_id' => 2,
      'brand_id' => 2,
      'description' => 'Product 2 Description',
      'created_by' => 1,
      'updated_by' => 1,
    ]);
    Product::factory()
      ->count(8)
      ->create();

  }
}
