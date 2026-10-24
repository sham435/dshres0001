<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvItemsTable extends Migration
{
    public function up()
    {
        Schema::create('inv_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('inv_schools')->onDelete('cascade');
            $table->string('item_name', 255);
            $table->string('brand_name', 255)->nullable();
            $table->string('inventory_number', 100)->unique();
            $table->string('serial_number', 100)->nullable();
            $table->foreignId('section_id')->nullable()->constrained('inv_sections')->onDelete('set null');
            $table->string('book_number', 100);
            $table->foreignId('incharge_officer_id')->nullable()->constrained('inv_officers')->onDelete('set null');
            $table->date('received_date')->nullable();
            $table->string('received_from', 255)->nullable();
            $table->boolean('is_transferred')->default(false);
            $table->date('transferred_date')->nullable();
            $table->string('borrowed_officer', 255)->nullable();
            $table->enum('condition_status', ['working', 'good', 'not_working', 'not_usable'])->default('working');
            $table->string('image_path', 500)->nullable();
            $table->timestamps();
            $table->index('section_id');
            $table->index('condition_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inv_items');
    }
}
