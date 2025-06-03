<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forum_questions', function (Blueprint $table) {
            $table->string('user_type', 255)->change();
        });

        Schema::table('forum_answers', function (Blueprint $table) {
            $table->string('user_type', 255)->change();
        });

        Schema::table('forum_question_likes', function (Blueprint $table) {
            $table->string('user_type', 255)->change();
        });

        Schema::table('forum_answer_likes', function (Blueprint $table) {
            $table->string('user_type', 255)->change();
        });
    }

    public function down()
    {
        Schema::table('forum_questions', function (Blueprint $table) {
            $table->string('user_type', 50)->change();
        });

        Schema::table('forum_answers', function (Blueprint $table) {
            $table->string('user_type', 50)->change();
        });

        Schema::table('forum_question_likes', function (Blueprint $table) {
            $table->string('user_type', 50)->change();
        });

        Schema::table('forum_answer_likes', function (Blueprint $table) {
            $table->string('user_type', 50)->change();
        });
    }
}; 