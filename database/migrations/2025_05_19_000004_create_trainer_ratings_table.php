<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trainer_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->foreignId('course_section_id')->constrained('course_sections')->onDelete('cascade');
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Ensure a student can only rate a trainer once per section
            $table->unique(['student_id', 'trainer_id', 'course_section_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trainer_ratings');
    }
}; 