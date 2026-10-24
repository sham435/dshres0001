<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('admission_applications', function (Blueprint $t) {
            $t->id();
            $t->string('first_name');
            $t->string('last_name');
            $t->string('phone')->nullable();
            $t->string('email');
            $t->date('dob');
            $t->string('academic_year', 10);
            $t->unsignedBigInteger('class_id');
            $t->string('shift')->nullable();
            $t->string('gender', 10);
            $t->string('religion', 30)->nullable();
            $t->string('nationality')->nullable();
            $t->string('home_language')->nullable();
            $t->string('place_of_birth')->nullable();
            $t->text('address')->nullable();
            $t->boolean('attended_school')->default(false);
            $t->text('previous_school')->nullable();
            $t->string('student_image')->nullable();
            $t->string('guardian_name');
            $t->string('guardian_phone');
            $t->string('guardian_profession')->nullable();
            $t->string('father_name')->nullable();
            $t->string('father_phone')->nullable();
            $t->string('mother_name')->nullable();
            $t->string('mother_phone')->nullable();
            $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $t->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('admission_applications'); }
}
