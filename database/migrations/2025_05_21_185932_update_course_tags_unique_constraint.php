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
        Schema::table('course_tags', function (Blueprint $table) {
            // Drop the existing unique index on the slug column
            $table->dropUnique(['slug']);

            // Add a new composite unique index on slug and course_id
            $table->unique(['slug', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_tags', function (Blueprint $table) {
            // Drop the composite unique index
            $table->dropUnique(['slug', 'course_id']);

            // Re-add the unique index on the slug column (optional, depending on desired schema rollback)
            // $table->unique('slug');
        });
    }
};
