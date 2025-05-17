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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Link to the user
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // e.g., "Personal", "Work"
            $table->string('timeframe'); // e.g., "Daily", "Weekly", "Monthly"
            $table->string('preferred_time')->nullable(); // e.g., "8:00am"
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
