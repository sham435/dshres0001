<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(7)->constrained('roles')->onDelete('cascade');
            $table->string('username', 50)->unique()->after('name')->nullable();
            $table->string('phone', 20)->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->date('dob')->nullable()->after('address');
            $table->string('gender', 10)->nullable()->after('dob');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'username', 'phone', 'address', 'dob', 'gender']);
        });
    }
}
