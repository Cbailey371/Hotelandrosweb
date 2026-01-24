<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name_es');
            $table->string('name_en');
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('capacity');
            $table->string('status')->default('active'); // active, inactive
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
