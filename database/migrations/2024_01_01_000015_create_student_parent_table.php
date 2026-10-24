<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentParentTable extends Migration
{
    public function up()
    {
        Schema::create('student_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['parent_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_parent');
    }
}
