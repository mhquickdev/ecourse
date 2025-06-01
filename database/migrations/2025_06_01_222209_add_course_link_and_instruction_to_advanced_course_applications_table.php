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
        Schema::table('advanced_course_applications', function (Blueprint $table) {
            $table->string('course_link')->nullable()->after('status');
            $table->text('instruction')->nullable()->after('course_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advanced_course_applications', function (Blueprint $table) {
            $table->dropColumn(['course_link', 'instruction']);
        });
    }
};
