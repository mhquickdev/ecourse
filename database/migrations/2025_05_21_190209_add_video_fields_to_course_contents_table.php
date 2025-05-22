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
        Schema::table('course_contents', function (Blueprint $table) {
            $table->string('video_source')->nullable()->after('type');
            $table->json('video_urls')->nullable()->after('video_source');
            $table->json('video_files')->nullable()->after('video_urls');
            $table->json('file_urls')->nullable()->after('video_files');
            $table->json('file_files')->nullable()->after('file_urls');
            $table->text('quiz_question')->nullable()->after('file_files');
            $table->json('quiz_options')->nullable()->after('quiz_question');
            $table->string('quiz_answer')->nullable()->after('quiz_options');
            $table->json('resources')->nullable()->after('quiz_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_contents', function (Blueprint $table) {
            $table->dropColumn([
                'video_source',
                'video_urls',
                'video_files',
                'file_urls',
                'file_files',
                'quiz_question',
                'quiz_options',
                'quiz_answer',
                'resources',
            ]);
        });
    }
};
