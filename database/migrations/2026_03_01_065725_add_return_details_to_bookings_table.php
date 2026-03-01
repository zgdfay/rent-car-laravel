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
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('return_date')->nullable()->after('end_date');
            $table->integer('late_fee')->default(0)->after('total_price');
            $table->integer('damage_fee')->default(0)->after('late_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['return_date', 'late_fee', 'damage_fee']);
        });
    }
};
