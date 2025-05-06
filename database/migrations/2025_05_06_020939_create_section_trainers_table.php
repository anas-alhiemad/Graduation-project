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
        Schema::create('section_trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections')->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_trainers');
    }
};
