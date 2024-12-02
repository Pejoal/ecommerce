<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfDataTable extends Migration {
  public function up() {
    Schema::create('pdf_data', function (Blueprint $table) {
      $table->id();
      $table->string('title')->nullable();
      $table->string('rechnungNr')->nullable();
      $table->string('kundenNr')->nullable();
      $table->date('datum')->nullable()->nullable();
      $table->string('monat')->nullable();
      $table->string('dienstleistungDatum')->nullable();
      $table->integer('stunden')->nullable();
      $table->decimal('stundenlohn', 8, 2)->nullable();
      $table->decimal('summe', 8, 2)->nullable();
      $table->decimal('zzglMwst', 5, 2)->nullable();
      $table->decimal('gesamtbetrag', 8, 2)->nullable();
      $table->string('verwendungszweck')->nullable();
      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('pdf_data');
  }
}