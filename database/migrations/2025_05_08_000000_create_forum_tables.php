<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forum_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('course_sections')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', ['student', 'trainer']);
            $table->string('title');
            $table->text('content');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });

        Schema::create('forum_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('forum_questions')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', ['student', 'trainer']);
            $table->text('content');
            $table->boolean('is_accepted')->default(false);
            $table->timestamps();
        });

        Schema::create('forum_question_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('forum_questions')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', ['student', 'trainer']);
            $table->timestamps();
            
            $table->unique(['question_id', 'user_id', 'user_type']);
        });

        Schema::create('forum_answer_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('forum_answers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type'); // 'student' or 'trainer'
            $table->timestamps();

            $table->unique(['answer_id', 'user_id', 'user_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('forum_answer_likes');
        Schema::dropIfExists('forum_question_likes');
        Schema::dropIfExists('forum_answers');
        Schema::dropIfExists('forum_questions');
    }
}; 