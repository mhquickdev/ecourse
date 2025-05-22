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
        Schema::table('course_demo_videos', function (Blueprint $table) {
            $table->string('video_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_demo_videos', function (Blueprint $table) {
            // To reverse, we would need to handle existing null values, which is complex.
            // For a simple down, we'll just make it not nullable again. Existing nulls might cause errors.
            $table->string('video_url')->nullable(false)->change();
        });
    }
};
