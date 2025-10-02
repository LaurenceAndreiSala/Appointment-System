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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
        $table->enum('payment_method', ['gcash','paypal','credit_card']);
        $table->decimal('amount', 8, 2)->default(500);
        $table->enum('payment_status',['success','failed','pending'])->default('pending');
        $table->string('reference_number')->unique();
        $table->timestamp('transaction_date')->useCurrent();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
