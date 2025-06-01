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
            $table->text('rejection_note')->nullable()->after('instruction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advanced_course_applications', function (Blueprint $table) {
            $table->dropColumn('rejection_note');
        });
    }
};
