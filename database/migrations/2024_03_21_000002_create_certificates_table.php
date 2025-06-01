<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique(); // Unique identifier for verification
            $table->string('file_path'); // Path to the generated PDF file
            $table->timestamp('issue_date');
            $table->timestamps();

            $table->unique(['user_id', 'course_id'], 'user_course_unique_certificate');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
    }
}; 