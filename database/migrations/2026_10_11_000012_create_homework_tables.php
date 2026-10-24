<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeworkTables extends Migration
{
    public function up()
    {
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_id')->constrained('homework')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->string('grade', 10)->nullable();
            $table->timestamps();
            $table->unique(['homework_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('homework_submissions');
        Schema::dropIfExists('homework');
    }
}
