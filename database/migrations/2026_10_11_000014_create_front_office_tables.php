<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontOfficeTables extends Migration
{
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 30);
            $table->string('email', 150)->nullable();
            $table->string('enquiry_for', 150)->nullable();
            $table->text('message')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->enum('status', ['new', 'contacted', 'admitted', 'closed'])->default('new');
            $table->timestamps();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 30)->nullable();
            $table->string('purpose', 255);
            $table->string('person_to_meet', 150)->nullable();
            $table->date('visit_date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->timestamps();
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complainant', 150);
            $table->string('phone', 30)->nullable();
            $table->string('subject', 255);
            $table->text('description');
            $table->enum('status', ['open', 'in_review', 'resolved', 'closed'])->default('open');
            $table->text('resolution')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('enquiries');
    }
}
