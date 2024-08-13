<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('cards', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->integer('order')->default(0);
      $table->foreignId('card_group_id')->constrained('card_groups')->onDelete('cascade');
      $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('cards');
  }
};