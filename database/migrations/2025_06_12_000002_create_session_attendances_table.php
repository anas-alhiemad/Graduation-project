<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('session_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->boolean('is_present')->default(false);
            $table->timestamps();

            
            $table->unique(['session_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_attendances');
    }
}; 