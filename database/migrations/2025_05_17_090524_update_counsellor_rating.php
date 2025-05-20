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
        Schema::table('counselor_profiles', function (Blueprint $table) {
           
            $table->integer('rating')->default(0)->after('bio');
            $table->integer('rating_count')->default(0)->after('rating');
           
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counselor_profiles', function (Blueprint $table) {
            $table->dropColumn(['rating', 'rating_count']);
            $table->dropColumn('rating');
        });
    }
};
