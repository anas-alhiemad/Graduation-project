<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('trainer_id');
            $table->decimal('grade', 5, 2);
            $table->dateTime('exam_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');

            $table->foreign('section_id')
                  ->references('id')
                  ->on('course_sections')
                  ->onDelete('cascade');

            $table->foreign('trainer_id')
                  ->references('id')
                  ->on('trainers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_grades');
    }
}; 