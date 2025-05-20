<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->date('date');
            $table->string('photo')->nullable();
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('cascade');
            $table->foreignId('secretary_id')->nullable()->constrained('secretaries')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gifts');
    }
}; 