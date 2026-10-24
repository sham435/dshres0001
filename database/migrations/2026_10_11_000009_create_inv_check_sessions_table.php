<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvCheckSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('inv_check_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('inv_schools')->onDelete('cascade');
            $table->string('session_name', 255);
            $table->date('check_date');
            $table->string('conducted_by', 255)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });

        Schema::create('inv_check_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('inv_check_sessions')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->string('item_name', 255);
            $table->string('inventory_number', 100);
            $table->integer('book_balance')->default(0);
            $table->integer('real_balance')->default(0);
            $table->integer('surplus')->default(0);
            $table->integer('deficiency')->default(0);
            $table->text('remarks')->nullable();
            $table->enum('checked_status', ['pending', 'checked', 'verified'])->default('pending');
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
            $table->index('session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inv_check_items');
        Schema::dropIfExists('inv_check_sessions');
    }
}
