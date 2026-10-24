<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvRemovalsTables extends Migration
{
    public function up()
    {
        Schema::create('inv_removals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_item_id');
            $table->unsignedBigInteger('school_id');
            $table->string('item_name', 255);
            $table->string('brand_name', 255)->nullable();
            $table->string('inventory_number', 100);
            $table->string('serial_number', 100)->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('book_number', 100)->nullable();
            $table->unsignedBigInteger('incharge_officer_id')->nullable();
            $table->string('condition_status', 50)->nullable();
            $table->text('recommendation_reason');
            $table->string('recommended_by', 100)->nullable();
            $table->timestamp('recommendation_date')->useCurrent();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('original_item_id');
            $table->index('status');
        });

        Schema::create('inv_removed_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('original_item_id')->nullable();
            $table->unsignedBigInteger('school_id');
            $table->string('item_name', 255);
            $table->string('brand_name', 255)->nullable();
            $table->string('inventory_number', 100);
            $table->string('serial_number', 100)->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('section_name', 255)->nullable();
            $table->string('book_number', 100)->nullable();
            $table->unsignedBigInteger('incharge_officer_id')->nullable();
            $table->string('incharge_officer_name', 255)->nullable();
            $table->string('condition_status', 50)->nullable();
            $table->text('removal_reason');
            $table->string('removed_by', 100)->nullable();
            $table->timestamp('removal_date')->useCurrent();
            $table->string('approval_status', 50)->nullable();
            $table->string('approved_by', 100)->nullable();
            $table->string('disposal_method', 100)->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('original_created_at')->nullable();
            $table->dateTime('original_updated_at')->nullable();
            $table->timestamp('archived_at')->useCurrent();
            $table->timestamps();
            $table->index('inventory_number');
            $table->index('removal_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inv_removed_archive');
        Schema::dropIfExists('inv_removals');
    }
}
