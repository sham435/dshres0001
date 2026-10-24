<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvOfficersTable extends Migration
{
    public function up()
    {
        Schema::create('inv_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('inv_schools')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('inv_sections')->onDelete('set null');
            $table->string('officer_name', 100);
            $table->string('officer_id_number', 50)->unique();
            $table->string('phone', 20);
            $table->boolean('is_incharge')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inv_officers');
    }
}
