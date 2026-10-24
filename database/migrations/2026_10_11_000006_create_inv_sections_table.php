<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('inv_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('inv_schools')->onDelete('cascade');
            $table->string('section_name', 100);
            $table->enum('section_type', ['Primary', 'Secondary', 'AL', 'Other'])->default('Other');
            $table->string('main_section', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inv_sections');
    }
}
