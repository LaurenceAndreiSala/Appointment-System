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
    Schema::create('availabilities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
        $table->date('date');
        $table->time('time_start');
        $table->time('end_time');
        $table->enum('status', ['available','unavailable'])->default('available');
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('availabilities');
    }
};
