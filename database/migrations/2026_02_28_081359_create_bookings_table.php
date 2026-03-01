<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('car_id');
            $table->string('car_name');
            $table->string('car_image')->nullable();
            $table->integer('car_price_per_day');
            $table->string('name');
            $table->string('whatsapp');
            $table->text('address');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration');
            $table->integer('total_price');
            $table->string('payment_proof')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
