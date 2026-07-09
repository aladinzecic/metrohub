<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('faults', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
        $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
        $table->enum('type', ['brakes', 'ac', 'validator', 'doors', 'seats', 'engine', 'lights', 'other']);
        $table->enum('severity', ['critical', 'moderate', 'minor']);
        $table->text('description');
        $table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');
        $table->timestamp('reported_at');
        $table->timestamp('resolved_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faults');
    }
};
