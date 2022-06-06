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
        Schema::create('booking_in', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('container_number');
            $table->string('number_plate');
            $table->string('transport_company');
            $table->date('date_in');
            $table->integer('price');
            $table->json('service_id');
            $table->integer('billing_type_id');
            $table->integer('marketing_id');
            $table->boolean('is_complete');
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
        Schema::dropIfExists('booking_in');
    }
};
