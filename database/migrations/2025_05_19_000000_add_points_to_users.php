<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('points')->default(0);
            $table->foreignId('referrer_id')->nullable()->constrained('students')->nullOnDelete();
        });

        Schema::table('secretaries', function (Blueprint $table) {
            $table->integer('points')->default(0);
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('points');
            $table->dropForeign(['referrer_id']);
            $table->dropColumn('referrer_id');
        });

        Schema::table('secretaries', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
}; 