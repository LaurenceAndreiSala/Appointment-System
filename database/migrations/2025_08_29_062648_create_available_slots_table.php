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
        Schema::create('available_slots', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('doctor_id')->nullable(); // if slots are tied to a doctor
    $table->date('date');
    $table->time('start_time');
    $table->time('end_time');
    $table->integer('max_patients')->default(1);
    $table->boolean('is_archived')->default(0);
    $table->boolean('is_taken')->default(false);
    $table->timestamps();

    $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_slots');
    }
};
