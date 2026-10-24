<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('location')->nullable();
            $t->date('event_date');
            $t->time('start_time')->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('events'); }
}
