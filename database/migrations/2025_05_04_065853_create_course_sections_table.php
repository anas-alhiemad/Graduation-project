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
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('seatsOfNumber');
            $table->enum('state', ['pending', 'in_progress', 'finished'])->default('pending');
            $table->date('startDate');
            $table->date('endDate');
            $table->foreignId('courseId')->constrained('courses')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_sections');
    }
};
