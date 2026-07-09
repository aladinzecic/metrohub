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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->enum('type', ['single', 'day', 'weekly', 'monthly']);
        $table->enum('zone', ['A', 'B', 'A+B']);
        $table->decimal('price', 8, 2);
        $table->date('valid_from');
        $table->date('valid_until');
        $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
        $table->string('qr_code')->unique();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
