<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('answer_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade');
            $table->morphs('user');
            $table->timestamps();

            $table->unique(['answer_id', 'user_id', 'user_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('answer_likes');
    }
}; 