<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('available_slots', function (Blueprint $table) {
        $table->unsignedBigInteger('sub_doctor_id')->nullable()->after('doctor_id');

        $table->foreign('sub_doctor_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('available_slots', function (Blueprint $table) {
        $table->dropForeign(['sub_doctor_id']);
        $table->dropColumn('sub_doctor_id');
    });
}
};
