<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('question_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->morphs('user');
            $table->timestamps();

            $table->unique(['question_id', 'user_id', 'user_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_likes');
    }
}; 