<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('tax_percentage', 5, 2)->default(7.00)->after('price');
            $table->decimal('extra_person_charge', 10, 2)->default(0.00)->after('tax_percentage');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('nights')->default(1)->after('check_out');
            $table->decimal('base_price', 10, 2)->default(0.00)->after('nights');
            $table->decimal('extra_person_total', 10, 2)->default(0.00)->after('base_price');
            $table->decimal('tax_amount', 10, 2)->default(0.00)->after('extra_person_total');
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('tax_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['tax_percentage', 'extra_person_charge']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['nights', 'base_price', 'extra_person_total', 'tax_amount', 'total_amount']);
        });
    }
};
