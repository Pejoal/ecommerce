<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('title', 200);
      $table->string('slug', 400);
      $table->integer('quantity');
      $table->longText('description')->nullable();
      $table->boolean('published')->default(0);
      $table->boolean('inStock')->default(0);
      $table->decimal('price', 10, 2);

      $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
      $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');

      $table->foreignIdFor(Brand::class, 'brand_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete()->nullable();
      $table->foreignIdFor(Category::class, 'category_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete()->nullable();
      $table->softDeletes();
      $table->foreignIdFor(User::class, 'deleted_by')->constrained()->cascadeOnUpdate()->cascadeOnDelete()->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('products');
  }
};
