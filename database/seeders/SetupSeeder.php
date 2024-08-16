<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Currency;
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
      'active' => 1,
    ]);
    Category::create([
      'name' => 'Phone',
      'slug' => 'phone',
      'active' => 1,
    ]);
    Category::create([
      'name' => 'Watch',
      'slug' => 'watch',
      'active' => 1,
    ]);

    Brand::create([
      'name' => 'Dell',
      'slug' => 'dell',
      'active' => 1,
    ]);
    Brand::create([
      'name' => 'Samsung',
      'slug' => 'samsung',
      'active' => 1,
    ]);
    Brand::create([
      'name' => 'Apple',
      'slug' => 'apple',
      'active' => 1,
    ]);

    Currency::create([
      'code' => 'USD',
      'name' => 'United States Dollar',
      'symbol' => '$',
    ]);
    Currency::create([
      'code' => 'EUR',
      'name' => 'Euro',
      'symbol' => '€',
    ]);
    Currency::create([
      'code' => 'GBP',
      'name' => 'British Pound Sterling',
      'symbol' => '£',
    ]);
    Currency::create([
      'code' => 'JPY',
      'name' => 'Japanese Yen',
      'symbol' => '¥',
    ]);
    Currency::create([
      'code' => 'AUD',
      'name' => 'Australian Dollar',
      'symbol' => 'A$',
    ]);

    Product::factory()
      ->count(25)
      ->create();

  }
}
