<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->foreignId('course_section_id')->constrained('course_sections')->onDelete('cascade');
            $table->boolean('is_present')->default(false);
            $table->date('date');
            $table->timestamps();

            // Ensure a student can only have one attendance record per section per date
            $table->unique(['student_id', 'course_section_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}; 