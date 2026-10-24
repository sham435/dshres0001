<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeaveGovtRules extends Migration
{
    public function up()
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->integer('max_consecutive_days')->nullable()->after('days_allowed');
            $table->string('rule_note', 500)->nullable()->after('max_consecutive_days');
        });

        Schema::table('leave_applications', function (Blueprint $table) {
            $table->boolean('is_abroad')->default(false)->after('reason');
        });
    }

    public function down()
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropColumn('is_abroad');
        });
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn(['max_consecutive_days', 'rule_note']);
        });
    }
}
