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
        Schema::table('galleries', function (Blueprint $box) {
            $box->boolean('show_in_cafe')->default(false)->after('carousel_order');
            $box->integer('cafe_order')->default(0)->after('show_in_cafe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $box) {
            $box->dropColumn(['show_in_cafe', 'cafe_order']);
        });
    }
};
