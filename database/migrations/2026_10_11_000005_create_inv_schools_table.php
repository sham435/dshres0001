<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('inv_schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name', 255);
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email', 100);
            $table->string('principal_name', 100);
            $table->string('principal_phone', 20);
            $table->string('inventory_officer_name', 100);
            $table->string('inventory_officer_phone', 20);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('logo_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inv_schools');
    }
}
