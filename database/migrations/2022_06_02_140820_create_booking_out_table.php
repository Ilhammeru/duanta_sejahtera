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
        Schema::create('booking_out', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('container_number')->nullable();
            $table->string('number_plate')->nullable();
            $table->string('transport_company')->nullable();
            $table->date('date_in')->nullable();
            $table->integer('price')->nullable();
            $table->integer('billing_type_id')->nullable();
            $table->integer('marketing_id')->nullable();
            $table->boolean('is_complete')->default(FALSE);
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
        Schema::dropIfExists('booking_out');
    }
};
