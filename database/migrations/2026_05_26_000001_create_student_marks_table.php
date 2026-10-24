<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentMarksTable extends Migration
{
    public function up()
    {
        Schema::create('student_marks', function (Blueprint $table) {
            $table->id();
            $table->string('student_name', 200);
            $table->string('admission_no', 50)->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('grade', 20)->nullable();
            $table->string('section', 10)->nullable();
            $table->string('subject', 100);
            $table->decimal('mark', 5, 1)->nullable();
            $table->string('grade_letter', 10)->nullable();
            $table->string('term', 50)->default('2nd Term');
            $table->year('year')->default(2022);
            $table->string('school', 100)->default('A/NACHCHADUWA MUSLIM MAHA VIDYALAYA');
            $table->integer('sheet_index')->nullable();
            $table->timestamps();

            $table->unique(['admission_no', 'subject', 'term', 'year', 'grade', 'section'], 'sm_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_marks');
    }
}
