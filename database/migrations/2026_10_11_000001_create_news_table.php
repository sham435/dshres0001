<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('excerpt')->nullable();
            $t->longText('body');
            $t->string('image')->nullable();
            $t->date('published_at');
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('news'); }
}
