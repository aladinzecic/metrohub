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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->integer('number');
            $table->string('plate')->unique();
            $table->integer('seat_count');
            $table->enum('status', ['in_service', 'garage', 'reserve', 'cleaning']);
            $table->enum('type', ['bus', 'tram']);
            $table->enum('fuel_type', ['diesel', 'electric', 'hybrid']);
            $table->date('registration_expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
